<?php

namespace App\Form\Admin;

use App\Entity\TrickGroup;
use App\Form\TrickType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du groupe',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'allowEmptyString' => false,
                        'min' => '3',
                        'max' => '40',
                        'minMessage' => 'Le nom du groupe est trop court, minimum {{ limit }} caractères',
                        'maxMessage' => 'Le nom du groupe est trop long, maximum {{ limit }} caractères'
                    ]),
                    new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-success'],
                'row_attr' => ['class' => 'd-inline-block float-right']
            ])
            ->add('return', ButtonType::class, [
                'label' => 'Retour',
                'attr' => [
                    'onClick' => 'window.history.back();',
                    'type' => 'button',
                ],
                'row_attr' => ['class' => 'd-inline-block mr-2 float-right']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickGroup::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['name'],
                    'message' => 'Ce nom de groupe est déjà utilisé',
                ])
            ]
        ]);
    }
}
