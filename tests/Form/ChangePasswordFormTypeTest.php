<?php

namespace App\Tests\Form;

use App\Form\ChangePasswordFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ChangePasswordFormTypeTest extends TypeTestCase
{
    public function testSubmitValidDate()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'password',
                'second' => 'password'
            ],
        ];


        $form = $this->factory->create(ChangePasswordFormType::class);


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($formData['plainPassword']['first'], $form->get('plainPassword')->getData());

    }

    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension(Validation::createValidator())
        ];
    }
}
