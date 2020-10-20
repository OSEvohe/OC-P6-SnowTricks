<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    const NOTEMPTY_MESSAGE = "Ce champ ne doit pas être vide.";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Trick',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'allowEmptyString' => false,
                        'min' => '3',
                        'max' => '50',
                        'minMessage' => 'Le nom de Trick est trop court, minimum {{ limit }} caractères',
                        'maxMessage' => 'Le nom du Trick est trop long, maximum {{ limit }} caractères'
                    ]),
                    new NotBlank(['message' => self::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du Trick',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'allowEmptyString' => false,
                        'min' => '5',
                        'max' => '65535',
                        'minMessage' => 'La description est trop courte, minimum {{ limit }} caractères',
                        'maxMessage' => 'Le nom du Trick est trop long, maximum {{ limit }} caractères'
                    ]),
                    new NotBlank(['message' => self::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label' => 'Groupe'
            ])
            ->add('cover', TrickMediaImageType::class, [
                'label' => 'Image principale'
            ])
            ->add('trickMedia', CollectionType::class, [
                'entry_type' => TrickMediaType::class,
                'mapped' => true,
                'label' => false,
            ])
            ->add('trickMediaPicture', CollectionType::class, [
                'entry_type' => TrickMediaImageType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
                'label' => false,
            ])
            ->add('trickMediaVideo', CollectionType::class, [
                'entry_type' => TrickMediaVideoType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
