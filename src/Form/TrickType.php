<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label' => 'Groupe',
            ])
            ->add('cover', MediaType::class, [
                'label' => 'Image principale',
                'cover' => true,
            ])
            ->add('trickMedia', CollectionType::class, [
                'entry_type' => MediaType::class,
                'mapped' => !$options['new'],
                'label' => 'Medias associés au trick',
                'allow_add' => true,
                'entry_options' => ['new' => $options['new']]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'new' => false,
            'data_class' => Trick::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['name'],
                    'message' => 'Ce nom de Trick est déjà utilisé',
                ])
            ]
        ]);
    }
}
