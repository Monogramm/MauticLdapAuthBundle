<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      Monogramm
 * @link        https://www.monogramm.io
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

namespace MauticPlugin\MauticLdapAuthBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractSsoFormIntegration;
use Mautic\UserBundle\Entity\User;

use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class LdapAuthIntegration
 */
class LdapAuthIntegration extends AbstractSsoFormIntegration
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'LdapAuth';
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return 'LDAP Authentication';
    }

    /**
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getSecretKeys()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthTokenKey()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     *
     * @param array $settings
     * @param array $parameters
     *
     * @return bool|array false if no error; otherwise the error string
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authCallback($settings = [], $parameters = [])
    {
        $hostname = $settings['hostname'];
        $port = isset($settings['port']) ? (int)$settings['port'] : 389;
        $ssl = isset($settings['ssl']) ? (bool)$settings['ssl'] : false;
        $startTls = isset($settings['starttls']) ? (bool)$settings['starttls'] : false;
        $ldapVersion = isset($settings['version']) && !empty($settings['version']) ?
            (int)$settings['version'] : 3;
        $base_dn = $settings['base_dn'];
        $userKey = $settings['user_key'];
        $query = $settings['user_query'];
        $password = $parameters['password'];
        $isactivedirectory = $settings['isactivedirectory'];
        $activedirectory_dn = $settings['activedirectory_domain'];

        if (substr($hostname, 0, 8) === 'ldaps://') {
            $ssl = true;
        }

        if (empty($port)) {
            if ($ssl) {
                $port = 636;
                $startTls = false;
            } else {
                $port = 389;
            }
        }

        if ($ssl) {
            if (substr($hostname, 0, 7) === 'ldap://') {
                $hostname = str_replace('ldap://', 'ldaps://', $hostname);
            } elseif (substr($hostname, 0, 8) !== 'ldaps://') {
                $hostname = 'ldaps://' . $hostname;
            }
        }

        $login = $parameters['login'];
        if (!empty($hostname) && !empty($login)) {
            $ldap = new LdapClient($hostname, $port, $ldapVersion, $ssl, $startTls);

            try {
                if ($isactivedirectory) {
                    $dn = "$login@$activedirectory_dn";
                } else {
                    $dn = "$userKey=$login,$base_dn";
                }

                $userquery = "$userKey=$login";
                $query = "(&($userquery)$query)"; // original $query already has brackets!

                $ldap->bind($dn, $password);
                $response = $ldap->find($base_dn, $query);
                // If we reach this far, we expect to have found something
                // and join the settings to the response to retrieve user fields
                if (is_array($response)) {
                    $response['settings'] = $settings;
                }
            } catch (\Exception $e) {
                $response = array(
                    'errors' => array(
                        $this->factory->getTranslator()->trans(
                            'mautic.integration.sso.ldapauth.error.authentication_issue',
                            [],
                            'flashes'
                        ),
                        $e->getMessage()
                    )
                );
            }

            return $this->extractAuthKeys($response);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param $data
     * @param $tokenOverride
     *
     * @return bool|array false if no error; otherwise the error string
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function extractAuthKeys($data, $tokenOverride = null)
    {
        // Prepare the keys for extraction such as renaming, setting expiry, etc
        $data = $this->prepareResponseForExtraction($data);

        // Parse the response
        if (is_array($data) && !empty($data) && isset($data['settings'])) {
            return array(
                'data' => $data[0],
                'settings' => $data['settings']
            );
        }

        $error = $this->getErrorsFromResponse($data);
        if (empty($error)) {
            $error = $this->factory->getTranslator()->trans(
                'mautic.integration.error.genericerror',
                [],
                'flashes'
            );
        }

        $fallback = $this->shouldFallbackToLocalAuth();
        if (!$fallback) {
            throw new AuthenticationException($error);
        } else {
            $this->getLogger()->addDebug($error);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $response
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function getUser($response)
    {
        if (is_array($response) && isset($response['settings']) && isset($response['data'])) {
            $settings = $response['settings'];
            $userKey = $settings['user_key'];
            $userEmail = $settings['user_email'];
            $userFirstname = $settings['user_firstname'];
            $userLastname = $settings['user_lastname'];
            $userFullname = $settings['user_fullname'];

            $data = $response['data'];
            $login = isset($data[$userKey]) ? $data[$userKey][0] : null;

            if (empty($login)) {
                // Login could not be found so bail
                return false;
            }

            $firstname = isset($data[$userFirstname]) ? $data[$userFirstname][0] : null;
            $lastname = isset($data[$userLastname]) ? $data[$userLastname][0] : null;

            if ((empty($firstname) || empty($lastname)) && isset($data[$userFullname])) {
                $names = explode(' ', $data[$userFullname][0]);
                if (count($names) > 1) {
                    $firstname = $names[0];
                    unset($names[0]);
                    $lastname = implode(' ', $names);
                } else {
                    $firstname = $lastname = $names[0];
                }
            }

            $email = isset($data[$userEmail]) ? $data[$userEmail][0] : null;

            if (empty($email)) {
                // Email could not be found so bail
                return false;
            }

            $user = new User();
            $user->setUsername($login)
                ->setEmail($email)
                ->setFirstName($firstname)
                ->setLastName($lastname)
                ->setRole(
                    $this->getUserRole()
                );

            return $user;
        }

        return false;
    }

    /**
     * Returns if failed LDAP authentication should fallback to local authentication.
     *
     * @return bool
     */
    public function shouldFallbackToLocalAuth()
    {
        $featureSettings = $this->settings->getFeatureSettings();

        return (isset($featureSettings['auth_fallback'])) ? $featureSettings['auth_fallback'] : true;
    }

    /**
     * {@inheritdoc}
     *
     * @param Form|\Symfony\Component\Form\FormBuilder $builder
     * @param array $data
     * @param string $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea == 'features') {
            $builder->add(
                'auth_fallback',
                'yesno_button_group',
                [
                    'label' => 'mautic.integration.sso.ldapauth.auth_fallback',
                    'data' => (isset($data['auth_fallback'])) ? (bool)$data['auth_fallback'] : true,
                    'attr' => [
                        'tooltip' => 'mautic.integration.sso.ldapauth.auth_fallback.tooltip',
                    ],
                ]
            );

            $builder->add(
                'auto_create_user',
                'yesno_button_group',
                [
                    'label' => 'mautic.integration.sso.auto_create_user',
                    'data' => (isset($data['auto_create_user'])) ? (bool)$data['auto_create_user'] : false,
                    'attr' => [
                        'tooltip' => 'mautic.integration.sso.auto_create_user.tooltip',
                    ],
                ]
            );

            $builder->add(
                'new_user_role',
                'role_list',
                [
                    'label' => 'mautic.integration.sso.new_user_role',
                    'label_attr' => ['class' => 'control-label'],
                    'attr' => [
                        'class' => 'form-control',
                        'tooltip' => 'mautic.integration.sso.new_user_role.tooltip',
                    ],
                ]
            );
        }
    }
}
