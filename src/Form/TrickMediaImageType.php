<?php

namespace App\Form;

use App\Entity\TrickMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrickMediaImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', FileType::class, [
                'label' => 'Image principale',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '512k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide, les formats acceptés sont JPG, GIG et PNG uniquement.',
                    ]),
                ]

            ])
            ->add('alt')
            ->add('type',HiddenType::class, [
                'empty_data' => 1
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickMedia::class,
        ]);
    }
}
