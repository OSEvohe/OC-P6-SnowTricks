<?php

namespace App\Form;

use App\Entity\TrickMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description de l\'image (Alt)',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'min' => 3,
                        'max' => 200,
                        'minMessage' => 'La description (Alt) est trop courte, minimum {{ limit }} caractères',
                        'maxMessage' => 'La description (Alt) est trop longue, maximum {{ limit }} caractères',
                    ]),
                    new NotBlank(['message' => TrickType::NOTEMPTY_MESSAGE])
                ],
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                /** @var TrickMedia $media */
                $media = $event->getData();
                $form = $event->getForm();

                // When we create a new media
                if (!$media) {
                    $form->add('image', FileType::class, [
                        'attr' => ['class' => 'form-control-file'],
                        'label' => false,
                        'required' => false,
                        'mapped' => false,
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
                            new NotBlank([
                                'message' => TrickType::NOTEMPTY_MESSAGE,
                                'groups' => ['image']
                            ])
                        ]

                    ])
                        ->add('type', ChoiceType::class, [
                            'label' => 'Type de Media',
                            'choices' => [
                                'Image' => TrickMedia::MEDIA_TYPE_IMAGE,
                                'Vidéo' => TrickMedia::MEDIA_TYPE_VIDEO
                            ],
                            'expanded' => true,
                            'multiple' => false,
                        ]);
                }

                // Display URL input only when editing a video media or creating a new media
                if (!$media || $media->getType() == TrickMedia::MEDIA_TYPE_VIDEO) {
                    $form->add('content', UrlType::class, [
                        'label' => 'URI de la vidéo',
                        'attr' => ['class' => 'form-control'],
                        'trim' => true,
                        'required' => false,
                        'constraints' => [
                            new Url(['message' => 'Ceci n\'est pas une URL valide']),
                            new NotBlank([
                                'message' => TrickType::NOTEMPTY_MESSAGE,
                                'groups' => ['video']
                            ])
                        ]
                    ]);
                }

            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickMedia::class,
            'new' => false,

            'validation_groups' => function(FormInterface $form){
                if ($form->getConfig()->getOption('new')){
                    if ($form->get('image')->isEmpty() && !$form->get('content')->isEmpty()){
                        return ['Default', 'video'];
                    }
                    if ($form->get('content')->isEmpty() && !$form->get('image')->isEmpty()){
                        return ['Default', 'image'];
                    }
                    if (!$form->get('content')->isEmpty() && !$form->get('image')->isEmpty()){
                        $form->addError(new FormError('Les champs URL et Image sont tous les deux renseignés, vous devez ajouter soit une image soit une vidéo'));
                        return ['Default', 'image', 'video'];
                    }
                }
                return ['Default', 'image', 'video'];
            }
        ]);
    }
}
