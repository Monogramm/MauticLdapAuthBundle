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
?>

<?php if (count(array_intersect($fieldKeys, ['ldap_auth_username_attribute', 'ldap_auth_username_attribute']))) : ?>
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
            <!--
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_ssl']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_starttls']); ?>
                </div>
            </div>
            -->
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_version']); ?>
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
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_isactivedirectory']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($fields['ldap_auth_activedirectory_domain']); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

