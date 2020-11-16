<?php

namespace App\Tests\Form;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class CommentTypeTest extends TypeTestCase
{

    public function testSubmitValidDate()
    {
        $formData = [
            'content' => 'Ceci est un commentaire',
            ];

        $model = new Comment();
        $form = $this->factory->create(CommentType::class, $model);

        $expected = new Comment();
        $expected->setContent($formData['content']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }

    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension( Validation::createValidator())
        ];
    }

}
