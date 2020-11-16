<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\ProfileType;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;

class ProfileTypeTest extends TypeTestCase
{
    /**
     * @var Security
     */
    private $security;

    protected function setUp() : void
    {
        $this->security = $this->createMock(Security::class);
        parent::setUp();
    }

    public function testSubmitValidData(){

        $formData = [
            'displayName' => 'testeur',
            'email' => 'test'
        ];

        $this->security->method('isGranted')->willReturn(true);

        $model = new User();
        $form = $this->factory->create(ProfileType::class, $model, ['constraints' => null]);

        $expected = new User();
        $expected->setDisplayName($formData['displayName']);
        $expected->setEmail($formData['email']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }

    protected function getExtensions()
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
            new PreloadedExtension([new ProfileType($this->security)],[])
        ];
    }
}
