<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class UserRegistrationFormTypeTest extends TypeTestCase
{
    public function testSubmitValidDate()
    {
        $formData = [
            'email' => 'admin@snowtricks.fr',
            'plainPassword' => [
                'first' => 'password',
                'second' => 'password'
            ],
            'displayName' => 'admin'
        ];

        $model = new User();
        $form = $this->factory->create(UserRegistrationFormType::class, $model, ['constraints' => null]);


        $expected = new User();
        $expected->setEmail($formData['email']);
        $expected->setPlainPassword($formData['plainPassword']['first']);
        $expected->setDisplayName($formData['displayName']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);

    }

    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension(Validation::createValidator())
        ];
    }
}
