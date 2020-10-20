<?php

namespace App\Form;

use App\Entity\TrickMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickMediaVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', UrlType::class, [
                'label' => 'URI de la vidéo',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('alt', TextType::class)
            ->add('type', HiddenType::class, [
                'empty_data' => 2
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickMedia::class,
        ]);
    }
}
