
Mautic.testLdapServerConnection = function() {
    var data = {
        host:     mQuery('#config_ldapconfig_ldap_auth_host').val(),
        port:     mQuery('#config_ldapconfig_ldap_auth_port').val(),
        ssl:      mQuery('#config_ldapconfig_ldap_auth_ssl').val(),
        starttls: mQuery('#config_ldapconfig_ldap_auth_starttls').val(),
        version:  mQuery('#config_ldapconfig_ldap_auth_version').val()
    };

    mQuery('#ldapAuthTestButtonContainer .fa-spinner').removeClass('hide');

    // TODO Coming feature: Create AjaxController with connect method
    Mautic.ajaxActionRequest('ldap:connect', data, function(response) {
        var theClass = (response.success) ? 'has-success' : 'has-error';
        var theMessage = response.message;
        mQuery('#ldapAuthTestButtonContainer').removeClass('has-success has-error').addClass(theClass);
        mQuery('#ldapAuthTestButtonContainer .help-block .status-msg').html(theMessage);
        mQuery('#ldapAuthTestButtonContainer .fa-spinner').addClass('hide');
    });
};

Mautic.testLdapAuthentication = function() {
    var data = {
        host:       mQuery('#config_ldapconfig_ldap_auth_host').val(),
        port:       mQuery('#config_ldapconfig_ldap_auth_port').val(),
        ssl:        mQuery('#config_ldapconfig_ldap_auth_ssl').val(),
        starttls:   mQuery('#config_ldapconfig_ldap_auth_starttls').val(),
        version:    mQuery('#config_ldapconfig_ldap_auth_version').val(),
        base_dn:    mQuery('#config_ldapconfig_ldap_auth_base_dn').val(),
        user_query: mQuery('#config_ldapconfig_ldap_auth_user_query').val(),
        is_ad:      mQuery('#config_ldapconfig_ldap_auth_isactivedirectory').val(),
        ad_domain:  mQuery('#config_ldapconfig_ldap_auth_activedirectory_domain').val(),
        login:      mQuery('#config_ldapconfig_ldap_auth_user_query').val(),
        password:   mQuery('#config_ldapconfig_ldap_auth_user_query').val()
    };

    mQuery('#ldapAuthTestButtonContainer .fa-spinner').removeClass('hide');

    // TODO Coming feature: Create AjaxController with authenticate method
    Mautic.ajaxActionRequest('ldap:authenticate', data, function(response) {
        var theClass = (response.success) ? 'has-success' : 'has-error';
        var theMessage = response.message;
        mQuery('#ldapAuthTestButtonContainer').removeClass('has-success has-error').addClass(theClass);
        mQuery('#ldapAuthTestButtonContainer .help-block .status-msg').html(theMessage);
        mQuery('#ldapAuthTestButtonContainer .fa-spinner').addClass('hide');
    });
};

Mautic.disableTestAuthenticationButton = function() {
    mQuery('#ldapAuthTestButtonContainer .help-block .status-msg').html('');
    mQuery('#ldapAuthTestButtonContainer .help-block .save-config-msg').removeClass('hide');
    mQuery('#config_ldapconfig_ldap_auth_test_authenticate_button').prop('disabled', true).addClass('disabled');

};
