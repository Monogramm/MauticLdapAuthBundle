[![Packagist](https://img.shields.io/packagist/l/monogramm/mautic-ldapauth-bundle.svg)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/monogramm/mautic-ldapauth-bundle.svg)](https://packagist.org/packages/monogramm/mautic-ldapauth-bundle)
[![Build Status](https://travis-ci.org/Monogramm/MauticLdapAuthBundle.svg)](https://travis-ci.org/Monogramm/MauticLdapAuthBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Monogramm/MauticLdapAuthBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Monogramm/MauticLdapAuthBundle/?branch=master)
[![mautic](https://img.shields.io/badge/mautic-%3E%3D%202.11-blue.svg)](https://www.mautic.org/mixin/ldapauth/)

# Mautic LDAP Authentication Plugin

This Plugin enables LDAP authentication for mautic 2 and newer. Even though Mautic offers SAML authentication, the main objective is to offer an alternative to those who do not want to setup SSO in their company just for mautic :smiley:

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
        'ldap_auth_isactivedirectory' => false,
    // ...
```

A sample configuration for Active Directory is 
```php
    //'parameters' => array(
    // ...
        'ldap_auth_host' => 'ad.mysupercompany.com',
        'ldap_auth_port' => 389,
        'ldap_auth_version' => 3,
        'ldap_auth_ssl' => false,
        'ldap_auth_starttls' => false,
        'ldap_auth_base_dn' => 'cn=Users,dc=ldap,dc=mysupercompany,dc=com',
        'ldap_auth_user_query' => '(objectclass=user)',     // careful this can be case sensitive!
        'ldap_auth_username_attribute' => 'samaccountname', // this is case sensitive!
        'ldap_auth_email_attribute' => 'mail',
        'ldap_auth_firstname_attribute' => 'givenname',
        'ldap_auth_lastname_attribute' => 'sn',
        'ldap_auth_fullname_attribute' => 'displayname',
        'ldap_auth_isactivedirectory' => true,
    // ...
```

Once the parameters are set, open a new browser and check connection through LDAP. **Do not log out until LDAP configuration is valid!**

## Developments in progress

* Configuration screen to set LDAP Auth parameters

## Contributing

Ideas and suggestions are welcome. Feel free to create an issue or PR on Github using our [CONTRIBUTING](CONTRIBUTING.md) guidelines.

## License

See [LICENSE](LICENSE) file.

## Author(s)

* [Monogramm](https://github.com/Monogramm)
