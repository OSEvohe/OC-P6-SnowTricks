<?php

namespace App\Form;

use App\Entity\User;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    const NOTEMPTY_MESSAGE ="Ce champ ne doit pas être vide.";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(['message'=>'"{{ value }}" n\'est pas une adresse e-mail valide.']),
                    new NotBlank(['message' => self::NOTEMPTY_MESSAGE])]])
            ->add('password', PasswordType::class, [
                'empty_data' => '',
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'max' => 255, 'maxMessage' => 'Le mot de passe ne doit pas dépasser {{limit}} caractères',
                        'allowEmptyString' => false,
                    ]),
                    new PasswordRequirements([
                        'minLength' => 8,
                        'tooShortMessage' => 'Le mot de passe doit avoir au moins {{length}} caractères.',
                        'requireLetters' => true,
                        'missingLettersMessage' => 'Le mot de passe doit contenir au moins une lettre.',
                        'requireCaseDiff' => true,
                        'requireCaseDiffMessage' => 'Le mot de passe doit contenir des lettres majuscules et minuscules.',
                        'requireNumbers' => true,
                        'missingNumbersMessage' => 'Le mot de passe doit contenir au moins un chiffre.',
                        'requireSpecialCharacter' => true,
                        'missingSpecialCharacterMessage' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                    ]),
                    new NotBlank(['message' => self::NOTEMPTY_MESSAGE])]])
            ->add('displayName', TextType::class, [
                'constraints' => [
                    new Length([
                        'normalizer' => 'trim',
                        'min' => 2, 'minMessage' => 'Le Pseudo doit contenir au minimum {{ limit }} caractères',
                        'max' => 30, 'maxMessage' => 'Le Pseudo doit contenir au maximum {{ limit }} caractères',
                        'allowEmptyString' => false
                    ]),
                    new NotBlank(['message' => self::NOTEMPTY_MESSAGE])]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('email'),
                    'message' => 'Cette adresse e-mail est déjà utilisée'
                )),
                new UniqueEntity(array(
                    'fields' => array('displayName'),
                    'message' => 'Ce pseudo est déjà utilisé par un autre utilisateur'
                ))
            )
        ]);
    }
}
