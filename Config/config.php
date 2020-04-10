<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      Monogramm
 * @contributor      enguerr
 *
 * @link        https://www.monogramm.io
 * @link        https://www.septeo.fr
 *
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

return array(
    'name'        => 'LdapAuth',
    'description' => 'Enables LDAP authentication',
    'version'     => '1.0',
    'author'      => 'Monogramm',
    'routes' => [
        'public' => array(
            'ldap_auth_user' => [
                'path'       => '/s/login',
                'controller' => 'MauticLdapAuthBundle:LdapAuth:auth'
            ],
            'ldap_auth_user_sso' => [
                'path'       => '/sso_login/{integration}',
                'controller' => 'MauticLdapAuthBundle:LdapAuth:sso'
            ],
        ),
    ],
    'services'    => array(
        'events' => array(
            'mautic.ldapauth.user.subscriber' => array(
                'class' => 'MauticPlugin\MauticLdapAuthBundle\EventListener\UserSubscriber',
                'arguments' => array(
                    'mautic.helper.core_parameters'
                ),
            ),
            'mautic.ldapauth.config.subscriber' => array(
                'class' => 'MauticPlugin\MauticLdapAuthBundle\EventListener\ConfigSubscriber',
            ),
        ),
        'forms'  => array(
            'mautic.form.type.ldapconfig' => array(
                'class'     => 'MauticPlugin\MauticLdapAuthBundle\Form\Type\ConfigType',
                'alias'     => 'ldapconfig',
                'arguments' => array(
                    'mautic.helper.core_parameters',
                    'translator',
                ),
            ),
        ),

    ),
    'parameters' => array(
        'ldap_auth_host' => null,
        'ldap_auth_port' => 389,
        'ldap_auth_version' => 3,
        'ldap_auth_ssl' => false,
        'ldap_auth_starttls' => true,
        // TODO Coming feature: Bind DN
        'ldap_auth_bind_dn' => 'cn=admin,dc=ldap,dc=company,dc=com',
        'ldap_auth_bind_passwd' => null,
        'ldap_auth_base_dn' => null,
        'ldap_auth_user_query' => '(objectclass=inetOrgPerson)',
        'ldap_auth_isactivedirectory' => false,
        'ldap_auth_activedirectory_domain' => null,
        'ldap_auth_username_attribute' => 'uid',
        'ldap_auth_email_attribute' => 'mail',
        'ldap_auth_firstname_attribute' => 'givenname',
        'ldap_auth_lastname_attribute' => 'sn',
        'ldap_auth_fullname_attribute' => 'displayname',
    )
);
