<?php
/**
 * @copyright   2020 Monogramm. All rights reserved
 * @author      Monogramm
 *
 * @see         https://www.monogramm.io
 *
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

namespace MauticPlugin\MauticLdapAuthBundle\EventListener;

use Mautic\ConfigBundle\ConfigEvents;
use Mautic\ConfigBundle\Event\ConfigBuilderEvent;
use Mautic\ConfigBundle\Event\ConfigEvent;
use MauticPlugin\MauticLdapAuthBundle\Form\Type\ConfigType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConfigSubscriber.
 */
class ConfigSubscriber implements EventSubscriberInterface
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
     * @param ConfigBuilderEvent $event Config generation event
     */
    public function onConfigGenerate(ConfigBuilderEvent $event)
    {
        $event->addForm(
            [
                'bundle'     => 'MauticLdapAuthBundle',
                'formAlias'  => 'ldapconfig',
                'formTheme'  => 'MauticLdapAuthBundle:FormTheme\Config',
                'formType'   => ConfigType::class,
                'parameters' => $event->getParametersFromConfig('MauticLdapAuthBundle'),
            ]
        );
    }

    /**
     * @param ConfigEvent $event Event on config saved
     */
    public function onConfigSave(ConfigEvent $event)
    {
        $data = $event->getConfig('ldapconfig');

        // Manipulate the values
        if (!empty($data['ldap_auth_host']) && 'ldaps://' === substr($data['ldap_auth_host'], 0, 8)) {
            $data['ldap_auth_ssl'] = true;
        }

        $event->setConfig($data, 'ldapconfig');
    }
}
