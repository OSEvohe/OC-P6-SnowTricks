<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Laissez un commentaire...'
                ],
                'label' => false,
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'min' => 4, 'minMessage' => 'Le commentaire est trop court, {{ limit }} caractère au minimum',
                        'max' => 200, 'maxMessage' => 'Le commentaire ne doit pas depasser {{ limit }} caractères',
                        'allowEmptyString' => false,
                    ]),
                    new NotBlank(['message' => 'Ce champ ne doit pas être vide.'])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
