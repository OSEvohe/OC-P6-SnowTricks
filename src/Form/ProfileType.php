<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', FileType::class, [
                'attr' => ['class' => 'form-control-file'],
                'label' => 'Photo du profil',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '256k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide, les formats acceptés sont JPG, GIF et PNG.',
                    ]),
                    new NotBlank([
                        'message' => UserRegistrationFormType::NOTEMPTY_MESSAGE,
                    ])
                ]
            ])
            ->add('displayName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'min' => 2, 'minMessage' => 'Le Pseudo doit contenir au minimum {{ limit }} caractères',
                        'max' => 30, 'maxMessage' => 'Le Pseudo doit contenir au maximum {{ limit }} caractères',
                        'allowEmptyString' => false
                    ]),
                    new NotBlank(['message' => UserRegistrationFormType::NOTEMPTY_MESSAGE])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['displayName'],
                    'message' => 'Ce pseudo est déjà utilisé',
                ])
            ]
        ]);
    }
}