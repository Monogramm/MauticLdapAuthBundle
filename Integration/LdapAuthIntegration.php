<?php
/**
 * @copyright   2020 Monogramm. All rights reserved
 * @author      Monogramm
 *
 * @see         https://www.monogramm.io
 *
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

namespace MauticPlugin\MauticLdapAuthBundle\Integration;

use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Mautic\PluginBundle\Integration\AbstractSsoFormIntegration;
use Mautic\UserBundle\Entity\User;
use Mautic\UserBundle\Form\Type\RoleListType;
use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class LdapAuthIntegration.
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
        $hostname    = $settings['hostname'];
        $port        = (int) $settings['port'];
        $ssl         = (bool) $settings['ssl'];
        $startTls    = (bool) $settings['starttls'];
        $ldapVersion = !empty($settings['version']) ? (int) $settings['version'] : 3;

        if ('ldap://' === substr($hostname, 0, 7)) {
            $hostname = str_replace('ldap://', '', $hostname);
        } elseif ('ldaps://' === substr($hostname, 0, 8)) {
            $ssl      = true;
            $startTls = false;
            $hostname = str_replace('ldaps://', '', $hostname);
        }

        if (empty($port)) {
            if ($ssl) {
                $port = 636;
            } else {
                $port = 389;
            }
        }

        if (!empty($hostname) && !empty($parameters['login'])) {
            $ldap = new LdapClient($hostname, $port, $ldapVersion, $ssl, $startTls);

            $response = $this->ldapUserLookup($ldap, $settings, $parameters);

            return $this->extractAuthKeys($response);
        }

        return false;
    }

    /**
     * LDAP authentication and lookup user information.
     *
     * @param LdapClient $ldap       LDAP client
     * @param array      $settings   LDAP connection settings
     * @param array      $parameters LDAP parameters
     *
     * @return array array containing the LDAP lookup results or error message(s)
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    private function ldapUserLookup($ldap, $settings = [], $parameters = [])
    {
        $base_dn   = $settings['base_dn'];
        $userKey   = $settings['user_key'];
        $query     = $settings['user_query'];
        $is_ad     = $settings['is_ad'];
        $ad_domain = $settings['ad_domain'];

        $login    = $parameters['login'];
        $password = $parameters['password'];

        try {
            if ($is_ad) {
                $user_dn = "$login@$ad_domain";
            } else {
                $user_dn = "$userKey=$login,$base_dn";
            }

            $userquery = "$userKey=$login";
            $query     = "(&($userquery)$query)"; // original $query already has brackets!

            $ldap->bind($user_dn, $password);
            $response = $ldap->find($base_dn, $query);
            // If we reach this far, we expect to have found something
            // and join the settings to the response to retrieve user fields
            if (is_array($response)) {
                $response['settings'] = $settings;
            }
        } catch (\Exception $e) {
            $response = [
                'errors' => [
                    $this->getTranslator()->trans(
                        'mautic.integration.sso.ldapauth.error.authentication_issue',
                        [],
                        'flashes'
                    ),
                    $e->getMessage(),
                ],
            ];
        }

        return $response;
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
            return [
                'data'     => $data[0],
                'settings' => $data['settings'],
            ];
        }

        $error = $this->getErrorsFromResponse($data);
        if (empty($error)) {
            $error = $this->getTranslator()->trans(
                'mautic.integration.error.genericerror',
                [],
                'flashes'
            );
        }

        $fallback = $this->shouldFallbackToLocalAuth();
        if (!$fallback) {
            throw new AuthenticationException($error);
        } else {
            $this->getLogger()->addError($error);
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
            $settings      = $response['settings'];
            $userKey       = $settings['user_key'];
            $userEmail     = $settings['user_email'];
            $userFirstname = $settings['user_firstname'];
            $userLastname  = $settings['user_lastname'];
            $userFullname  = $settings['user_fullname'];

            $data  = $response['data'];
            $login = self::arrayGet($data, $userKey, [null])[0];
            $email = self::arrayGet($data, $userEmail, [null])[0];

            if (empty($login) || empty($email)) {
                // Login or email could not be found so bail
                return false;
            }

            $firstname = self::arrayGet($data, $userFirstname, [null])[0];
            $lastname  = self::arrayGet($data, $userLastname, [null])[0];

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
     * Get a value from an array or return default value if not set.
     *
     * @param array  $array   source array
     * @param string $key     key to get from array
     * @param mixed  $default default value if key not set in array
     *
     * @return mixed a value from array or default value
     */
    private function arrayGet($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
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
     * @param Form|\Symfony\Component\Form\FormBuilder $builder  Configuration form builder
     * @param array                                    $data     Form data
     * @param string                                   $formArea Form area
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ('features' == $formArea) {
            $builder->add(
                'auth_fallback',
                YesNoButtonGroupType::class,
                [
                    'label' => 'mautic.integration.sso.ldapauth.auth_fallback',
                    'data'  => (isset($data['auth_fallback'])) ? (bool) $data['auth_fallback'] : true,
                    'attr'  => [
                        'tooltip' => 'mautic.integration.sso.ldapauth.auth_fallback.tooltip',
                    ],
                ]
            );

            $builder->add(
                'auto_create_user',
                YesNoButtonGroupType::class,
                [
                    'label' => 'mautic.integration.sso.auto_create_user',
                    'data'  => (isset($data['auto_create_user'])) ? (bool) $data['auto_create_user'] : false,
                    'attr'  => [
                        'tooltip' => 'mautic.integration.sso.auto_create_user.tooltip',
                    ],
                ]
            );

            $builder->add(
                'new_user_role',
                RoleListType::class,
                [
                    'label'      => 'mautic.integration.sso.new_user_role',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.integration.sso.new_user_role.tooltip',
                    ],
                ]
            );
        }
    }
}
