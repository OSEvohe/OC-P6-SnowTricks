<?php

namespace App\Tests\Form;

use App\Entity\TrickMedia;
use App\Form\MediaType;
use App\Service\YoutubeHelper;
use stdClass;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class MediaTypeTest extends TypeTestCase
{
    /**
     * @var YoutubeHelper
     */
    private $youtubeHelper;

    protected function setUp() : void
    {
        $this->youtubeHelper = $this->createMock(YoutubeHelper::class);
        $videoInfo = new stdClass();
        $videoInfo->title = 'Video Title';
        $this->youtubeHelper->method('getVideoInfo')->willReturn($videoInfo);
        parent::setUp();
    }


    public function testSubmitImage()
    {
        $formData = [
            'alt' => 'Ceci est un test',
            'type' => TrickMedia::MEDIA_TYPE_IMAGE
        ];

        $model = new TrickMedia();
        $form = $this->factory->create(MediaType::class, $model, ['new' => true]);

        $expected = new TrickMedia();
        $expected->setContent(null);
        $expected->setAlt($formData['alt']);
        $expected->setType($formData['type']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }

    public function testSubmitVideo()
    {
        $formData = [
            'type' => TrickMedia::MEDIA_TYPE_VIDEO,
            'content' => 'https://www.youtube.com/watch?v=lOox4UJVFb4',
            'alt' => ''
        ];

        $model = new TrickMedia();
        $form = $this->factory->create(MediaType::class, $model, ['new' => true]);

        $expected = new TrickMedia();
        $expected->setContent('https://www.youtube.com/watch?v=lOox4UJVFb4');
        $expected->setAlt('Video Title');
        $expected->setType($formData['type']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }

    protected function getExtensions()
    {
        return [
            new ValidatorExtension( Validation::createValidator()),
            new PreloadedExtension([new MediaType($this->youtubeHelper)],[])
        ];
    }
}
