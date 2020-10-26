<?php

namespace App\Form;

use App\Entity\TrickMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class TrickMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var TrickMedia $media */
                $media = $event->getData();
                $form = $event->getForm();
                $fieldType = UrlType::class;
                $options = [
                    'label' => 'URI de la vidéo',
                    'trim' => true,
                    'required' => true,
                    'constraints' => [
                        new Url(['message' => 'Ceci n\'est pas une URL valide']),
                        new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                    ]
                ];
                if ($media->getType() == TrickMedia::MEDIA_TYPE_IMAGE) {
                    $fieldType = FileType::class;
                    $options = [
                        'attr' => ['class' => 'form-control-file'],
                        'label' => false,
                        'mapped' => false,
                        'required' => false,
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
                    ];
                }
                $form->add('content', $fieldType, $options);
            })
            ->add('alt');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickMedia::class,
        ]);
    }
}
