<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    private $security;


    /**
     * ProfileType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            if ($this->security->isGranted(User::USER_VERIFIED)) {
                $form
                    ->add('photo', FileType::class, [
                        'label' => 'Nouvelle photo du profil',
                        'required' => false,
                        'mapped' => false,
                        'constraints' => [
                            new File([
                                'maxSize' => '256k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/gif',
                                    'image/png'
                                ],
                                'mimeTypesMessage' => 'Type de fichier invalide, les formats acceptés sont JPG, GIF et PNG.',
                            ]),
                        ]
                    ])
                    ->add('displayName', TextType::class, [
                        'attr' => ['class' => 'form-control'],
                        'label' => 'Nouveau pseudo',
                        'help_attr' => ['class' => 'small'],
                        'help' => 'Laissez vide pour ne pas le modifier.',
                        'required' => false,
                        'constraints' => [
                            new Length([
                                'normalizer' => 'trim',
                                'min' => 2, 'minMessage' => 'Le Pseudo doit contenir au minimum {{ limit }} caractères',
                                'max' => 30, 'maxMessage' => 'Le Pseudo doit contenir au maximum {{ limit }} caractères',
                                'allowEmptyString' => false
                            ]),
                        ],
                    ]);
                if ($this->security->isGranted(User::USER_ADMIN)) {
                    $form->add('email', EmailType::class, [
                        'label' => 'Nouvelle adresse email',
                        'help_attr' => ['class' => 'small'],
                        'help' => 'Laissez vide pour ne pas la modifier.',
                        'required' => false,
                        'constraints' => [
                            new Email(['message' => '"{{ value }}" n\'est pas une adresse e-mail valide.']),
                        ]
                    ]);
                }
                $form->add('save', SubmitType::class, [
                    'label' => 'Enregistrer',
                    'attr' => ['class' => 'btn btn-success'],
                    'row_attr' => ['class' => 'd-inline-block float-right']
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['displayName'],
                    'message' => 'Ce pseudo est déjà utilisé',
                ])
            ]
        ]);
    }
}
