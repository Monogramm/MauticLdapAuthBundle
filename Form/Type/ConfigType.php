<?php
/**
 * @package     Mautic
 * @copyright   2019 Monogramm. All rights reserved
 * @author      Monogramm
 * @link        https://www.monogramm.io
 * @license     GNU/AGPLv3 http://www.gnu.org/licenses/agpl.html
 */

namespace MauticPlugin\MauticLdapAuthBundle\Form\Type;

use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ConfigType.
 */
class ConfigType extends AbstractType
{
    /**
     * @var CoreParametersHelper
     */
    protected $parameters;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * ConfigType constructor.
     *
     * @param CoreParametersHelper $parametersHelper
     * @param TranslatorInterface $translator
     */
    public function __construct(CoreParametersHelper $parametersHelper, TranslatorInterface $translator)
    {
        $this->parameters = $parametersHelper;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'ldap_auth_host',
            UrlType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.host',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'default_protocol' => 'ldap'
            ]
        );

        $builder->add(
            'ldap_auth_port',
            NumberType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.port',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'mail',
            ]
        );
        // FIXME Unable to transform value for property path "[ldap_auth_ssl]": Expected a Boolean.
/*
        $builder->add(
            'ldap_auth_ssl',
            CheckboxType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.ssl',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => false,
            ]
        );
*/
        // FIXME Unable to transform value for property path "[ldap_auth_starttls]": Expected a Boolean.
/*
        $builder->add(
            'ldap_auth_starttls',
            CheckboxType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.starttls',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => true,
            ]
        );
*/
        $builder->add(
            'ldap_auth_version',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.version',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 3,
                'required' => false,
            ]
        );

        $builder->add(
            'ldap_auth_base_dn',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.user_query',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'uid',
            ]
        );

        $builder->add(
            'ldap_auth_user_query',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.user_query',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'uid',
            ]
        );

        $builder->add(
            'ldap_auth_username_attribute',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.username_attribute',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'uid',
            ]
        );

        $builder->add(
            'ldap_auth_email_attribute',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.email_attribute',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'mail',
            ]
        );

        $builder->add(
            'ldap_auth_firstname_attribute',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.firstname_attribute',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'givenname',
            ]
        );

        $builder->add(
            'ldap_auth_lastname_attribute',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.lastname_attribute',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'sn',
            ]
        );

        $builder->add(
            'ldap_auth_fullname_attribute',
            TextType::class,
            [
                'label'      => 'mautic.integration.sso.ldapauth.config.form.fullname_attribute',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'empty_data' => 'displayname',
                'required' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ldapconfig';
    }
}
