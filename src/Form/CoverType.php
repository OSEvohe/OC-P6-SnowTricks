<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickMedia;
use App\Repository\TrickMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $trick = $event->getData();
                $event->getForm()->add('cover', EntityType::class, array(
                    'class' => TrickMedia::class,
                    'choice_label' => 'content',
                    'query_builder' => function (TrickMediaRepository $er) use ($trick) {
                        return $er->createQueryBuilder('u')
                            ->where('u.trick = :trick_id')
                            ->setParameter('trick_id', $trick->getId())
                            ->andWhere('u.type = :type')
                            ->setParameter('type', TrickMedia::MEDIA_TYPE_IMAGE);
                    },
                    'label' => 'Image principale',
                    'expanded' => true,
                    'multiple' => false,
                ));
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
