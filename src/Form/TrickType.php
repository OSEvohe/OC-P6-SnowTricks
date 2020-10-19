<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
            ])
            ->add('cover', TrickMediaImageType::class)
            ->add('trickMedia', CollectionType::class, [
                'entry_type' => TrickMediaType::class,
                'mapped' => true
            ])
            ->add('trickMediaPicture', CollectionType::class, [
                'entry_type' => TrickMediaImageType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
