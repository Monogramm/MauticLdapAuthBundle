# Changelog
All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [Unreleased]
- Sso behind nginx proxy  
- Auto login with /s/sso_login/LdapAuth
- Button added on login screen to login with AD
- Auto-provisionning from AD special account 

## [1.1.1](https://github.com/Monogramm/MauticLdapAuthBundle/releases/tag/1.1.1) - 2019-05-04
### Changed
- Updated contribution guidelines
- Improved code quality in `LdapAuthIntegration.php`

### Fixed
- Fix issue when hostname prefixed with ldaps://

## [1.1.0](https://github.com/Monogramm/MauticLdapAuthBundle/releases/tag/1.1.0) - 2019-05-04
### Added
- New properties for Active Directory (big thanks to @terdinatore for his contribution on #1)
- LDAP Settings now available in Mautic Configuration screen

### Fixed
- Fix issues when hostname prefixed with ldap:// or ldaps://
- Change package name for correct naming when installing from composer (#2)


## [1.0.0](https://github.com/Monogramm/MauticLdapAuthBundle/releases/tag/1.0.0) - 2019-04-02
### Added
- Mautic LDAP Authentication Mixin first release



