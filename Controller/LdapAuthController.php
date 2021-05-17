<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      enguerr
 *
 * @link        https://www.septeo.fr
 *
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */
namespace MauticPlugin\MauticLdapAuthBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Mautic\UserBundle\Entity\User;


class LdapAuthController extends CommonController
{
    private $parametersHelper;

    private $supportedServices = array(
        'LdapAuth',
    );

    public function __construct()
    {
    }
    public function authAction(Request $request)
    {
        $session = $this->request->getSession();
        $integrationHelper = $this->get('mautic.helper.integration');
        $integrations      = $integrationHelper->getIntegrationObjects(null, [], true, null, true);

        return $this->delegateView([
            'viewParameters' => [
                'last_username' => $session->get(Security::LAST_USERNAME),
                'integrations'  => $integrations,
            ],
            'contentTemplate' => 'MauticUserBundle:Security:login.html.php',
            'passthroughVars' => [
                'route'          => $this->generateUrl('login'),
                'mauticContent'  => 'user',
                'sessionExpired' => true,
            ],
        ]);
    }
    public function ssoAction($integration,Request $request)
    {
        $session = $this->request->getSession();
        if (isset($_SERVER['PHP_AUTH_USER'])){
            //$session->remove('_security.last_error');
            //$session->remove('_csrf/https-authenticate');

            //$session->clear();
            //die();
            //return $this->redirect( '/s/sso_login/LdapAuth',302);
        }


        $this->parametersHelper = $this->get('mautic.helper.core_parameters');
        //print_r($session);
        // Get a list of SSO integrations
        $integrationHelper = $this->get('mautic.helper.integration');
        $integrations      = $integrationHelper->getIntegrationObjects(null, [], true, null, true);
        $integration = $integrations['LdapAuth'];
        /*$settings = [
            'hostname'      => $this->parametersHelper->getParameter('ldap_auth_host'),
            'port'          => $this->parametersHelper->getParameter('ldap_auth_port', 389),
            'ssl'           => $this->parametersHelper->getParameter('ldap_auth_ssl', false),
            'starttls'      => $this->parametersHelper->getParameter('ldap_auth_starttls', true),
            'version'       => $this->parametersHelper->getParameter('ldap_auth_version', 3),
            // TODO Coming feature: Bind DN
            //'bind_dn'       => $this->parametersHelper->getParameter('ldap_auth_bind_dn'),
            //'bind_passwd'   => $this->parametersHelper->getParameter('ldap_auth_bind_passwd'),
            'base_dn'       => $this->parametersHelper->getParameter('ldap_auth_base_dn'),
            'user_query'    => $this->parametersHelper->getParameter('ldap_auth_user_query', ''),
            'is_ad'         => $this->parametersHelper->getParameter('ldap_auth_isactivedirectory', false),
            'ad_domain'     => $this->parametersHelper->getParameter('ldap_auth_activedirectory_domain', null),
            'user_key'      => $this->parametersHelper->getParameter('ldap_auth_username_attribute', 'uid'),
            'user_email'    => $this->parametersHelper->getParameter('ldap_auth_email_attribute', 'mail'),
            'user_firstname'=> $this->parametersHelper->getParameter('ldap_auth_firstname_attribute', 'givenName'),
            'user_lastname' => $this->parametersHelper->getParameter('ldap_auth_lastname_attribute', 'sn'),
            'user_fullname' => $this->parametersHelper->getParameter('ldap_auth_fullname_attribute', 'displayName'),
        ];
        $parameters = array(
            'login'=>$_SERVER['PHP_AUTH_USER'],
            'password'=>'',
            'email'=> 'e.messin@septeo.fr'
        );
        //GET USER
        //$user = $integration->getUserSimple($settings,$parameters);
        $user = new User();
        $user->setUsername($parameters['login'])
            ->setEmail($parameters['email'])
            ->setFirstName($parameters['login'])
            ->setLastName($parameters['login'])
            ->setRole(
                $integration->getUserRole()
            );

        $token = new UsernamePasswordToken($user, '', 'main',array());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);*/

        return $this->delegateView([
            'viewParameters' => [
                'last_username' => $session->get(Security::LAST_USERNAME),
                'integrations'  => $integrations,
            ],
            'contentTemplate' => 'MauticUserBundle:Security:login.html.php',
            'passthroughVars' => [
                'route'          => $this->generateUrl('login'),
                'mauticContent'  => 'user',
                'sessionExpired' => true,
            ],
        ]);
    }
}