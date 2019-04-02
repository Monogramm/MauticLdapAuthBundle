# Mautic LDAP Authentication Plugin

[![license](https://img.shields.io/packagist/v/monogramm/mautic-ldapauth-bundle.svg)](https://packagist.org/packages/monogramm/mautic-ldapauth-bundle) 
[![Packagist](https://img.shields.io/packagist/l/monogramm/mautic-ldapauth-bundle.svg)](LICENSE)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Monogramm/MauticLdapAuthBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Monogramm/MauticLdapAuthBundle/?branch=master) 
[![mautic](https://img.shields.io/badge/mautic-%3E%3D%202.11-blue.svg)](https://www.mautic.org/mixin/ldapauth/)

This Plugin enables LDAP authentication for mautic 2 and newer.

Ideas and suggestions are welcome, feel free to create an issue or PR on Github.

:construction: **This bundle is still in its early stages** 

## Installation via composer (preferred)
Execute `composer require monogramm/mautic-ldapauth-bundle` in the main directory of the mautic installation.

## Installation via .zip
1. Download the [master.zip](https://github.com/Monogramm/MauticLdapAuthBundle/archive/master.zip), extract it into the `plugins/` directory and rename the new directory to `MauticLdapAuthBundle`.
2. Clear the cache via console command `php app/console cache:clear --env=prod` (might take a while) *OR* manually delete the `app/cache/prod` directory.

## Configuration
Navigate to the Plugins page and click "Install/Upgrade Plugins". You should now see a "LDAP Auth" plugin.

Edit manually your parameters in `local.php` (adapt to your LDAP configuration):
```php
    //'parameters' => array(
    // ...
        'ldap_auth_host' => 'ldap.mysupercompany.com',
        'ldap_auth_port' => 389,
        'ldap_auth_version' => 3,
        'ldap_auth_ssl' => false,
        'ldap_auth_starttls' => true,
        'ldap_auth_base_dn' => 'ou=People,dc=ldap,dc=mysupercompany,dc=com',
        'ldap_auth_user_query' => '(objectclass=inetOrgPerson)',
        'ldap_auth_username_attribute' => 'uid',
        'ldap_auth_email_attribute' => 'mail',
        'ldap_auth_firstname_attribute' => 'givenname',
        'ldap_auth_lastname_attribute' => 'sn',
        'ldap_auth_fullname_attribute' => 'displayname',
    // ...
```

Once the parameters are set, open a new browser and check connection through LDAP. **Do not log out until LDAP connection is successful!**

## Developments in progress

* Configuration screen to set LDAP Auth parameters
