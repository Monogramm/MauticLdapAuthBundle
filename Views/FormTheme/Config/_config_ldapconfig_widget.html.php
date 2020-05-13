<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      Monogramm
 * @link        https://www.monogramm.io
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

$fields    = $form->children;
$fieldKeys = array_keys($fields);
$keys = [
    'ldap_auth_host',
    'ldap_auth_username_attribute',
    'ldap_auth_email_attribute',
    'ldap_auth_firstname_attribute',
    'ldap_auth_lastname_attribute'
];
?>

<?php if (count(array_intersect($fieldKeys, $keys))) : ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php
                echo $view['translator']->trans('mautic.integration.sso.ldapauth.config.header.ldap');
            ?></h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info"><?php
                echo $view['translator']->trans('mautic.integration.sso.ldapauth.config.form.ldap_info');
            ?></div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_host']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_port']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_ssl']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_starttls']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_version']); ?>
                </div>
                <!-- TODO Coming feature: test LDAP connection -->
                <!--
                <div class="col-md-6" id="ldapAuthTestButtonContainer">
                    <div class="button_container">
                        <?php //echo $view['form']->widget($fields['ldap_auth_test_connection_button']); ?>
                        <span class="fa fa-spinner fa-spin hide"></span>
                    </div>
                    <div class="col-md-9 help-block">
                        <div class="status-msg"></div>
                        <div class="save-config-msg hide text-danger"><?php
                            //echo $view['translator']->trans('mautic.ldap.config.save_to_test');
                        ?></div>
                    </div>
                </div>
                -->
            </div>
            <hr />

            <!-- TODO Coming feature: LDAP bind account and Group lookup -->
            <div class="alert alert-info"><?php
                echo $view['translator']->trans('mautic.integration.sso.ldapauth.config.form.ldap_authentication');
            ?></div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_bind_dn']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_bind_passwd']); ?>
                </div>
            </div>
            <hr />

            <div class="alert alert-info"><?php
                echo $view['translator']->trans('mautic.integration.sso.ldapauth.config.form.ldap_filters');
            ?></div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_base_dn']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_user_query']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_isactivedirectory']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_activedirectory_domain']); ?>
                </div>
            </div>
            <hr />

            <div class="alert alert-info"><?php
                echo $view['translator']->trans('mautic.integration.sso.ldapauth.config.form.ldap_attributes');
            ?></div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_username_attribute']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_email_attribute']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_firstname_attribute']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_lastname_attribute']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_fullname_attribute']); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

