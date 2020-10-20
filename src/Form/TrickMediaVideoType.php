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
use Symfony\Component\Validator\Constraints\Regex;

class TrickMediaVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', UrlType::class, [
                'label' => 'URI de la vidéo',
                'trim' => true,
                'required' => true,
                'constraints' => [
                    new Regex('%^(?:https?://)?(?:www\.)?(?:youtube\.com/watch\?v=([^&\n]+)|youtu\.be/([a-zA-Z\d]+))$%iu'),
                    new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                ]
            ])
            ->add('alt', TextType::class)
            ->add('type', HiddenType::class, [
                'empty_data' => 2,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickMedia::class,
        ]);
    }
}
