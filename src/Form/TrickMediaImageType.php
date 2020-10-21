<?php

namespace App\Form;

use App\Entity\TrickMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickMediaImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', FileType::class, [
                'attr' => ['class' => 'form-control-file'],
                'label' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '512k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide, les formats acceptés sont JPG, GIF et PNG.',
                    ]),
                    new NotBlank(['message' => 'Veuillez choisir une image.'])
                ]

            ])
            ->add('alt', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description de l\'image (Alt)',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'min' => 3,
                        'max' => 200,
                        'minMessage' => 'La description (Alt) est trop courte, minimum {{ limit }} caractères',
                        'maxMessage' => 'La description (Alt) est trop longue, maximum {{ limit }} caractères',
                    ]),
                    new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('type',HiddenType::class, [
                'empty_data' => 1,
                'label' => false
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
