<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      Monogramm
 * @link        https://www.monogramm.io
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

namespace MauticPlugin\MauticLdapAuthBundle\EventListener;

use Mautic\ConfigBundle\ConfigEvents;
use Mautic\ConfigBundle\Event\ConfigBuilderEvent;
use Mautic\ConfigBundle\Event\ConfigEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;

/**
 * Class ConfigSubscriber.
 */
class ConfigSubscriber extends CommonSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConfigEvents::CONFIG_ON_GENERATE => ['onConfigGenerate', 0],
            ConfigEvents::CONFIG_PRE_SAVE    => ['onConfigSave', 0],
        ];
    }

    /**
     * @param ConfigBuilderEvent $event
     */
    public function onConfigGenerate(ConfigBuilderEvent $event)
    {
        $event->addForm(
            [
                'bundle'     => 'MauticLdapAuthBundle',
                'formAlias'  => 'ldapconfig',
                'formTheme'  => 'MauticLdapAuthBundle:FormTheme\Config',
                'parameters' => $event->getParametersFromConfig('MauticLdapAuthBundle'),
            ]
        );
    }

    /**
     * @param ConfigEvent $event
     */
    public function onConfigSave(ConfigEvent $event)
    {
        $data = $event->getConfig('ldapconfig');

        // Manipulate the values
        if (!empty($data['ldap_auth_host']) && substr($data['ldap_auth_host'], 0, 8) === 'ldaps://') {
            $data['ldap_auth_ssl'] = true;
        }

        $event->setConfig($data, 'ldapconfig');
    }
}
