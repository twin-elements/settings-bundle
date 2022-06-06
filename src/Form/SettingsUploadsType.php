<?php

namespace TwinElements\SettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use TwinElements\Component\AdminTranslator\AdminTranslator;

class SettingsUploadsType extends AbstractType
{
    private AdminTranslator $translator;

    public function __construct(AdminTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', FileType::class, [
                'label' => $this->translator->translate('admin_settings.logo'),
                'help' => $this->translator->translate('admin_settings.logo_help'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/svg',
                            'image/svg+xml',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid SVG or PNG image',
                    ])
                ],
            ])
            ->add('sitemap', FileType::class, [
                'label' => $this->translator->translate('admin_settings.sitemap'),
                'help' => $this->translator->translate('admin_settings.sitemap_help'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/xml',
                            'text/xml'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid XML file',
                    ])
                ],
            ])
            ->add('save', SubmitType::class,[
                'label' => $this->translator->translate('admin_type.save')
            ])
        ;
    }
}
