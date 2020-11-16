<?php

namespace App\Tests\Service;

use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploaderTest extends KernelTestCase
{
    private $sluggerInterface;
    private $fileSystem;
    /**
     * @var ImageUploader
     */
    private $imageUploader;


    /**
     * ImageUploaderTest constructor.
     */
    public function __construct()
    {
        $this->sluggerInterface = $this->createMock(\Symfony\Component\String\Slugger\SluggerInterface::class);
        $this->fileSystem = $this->createMock(Filesystem::class);
        $this->imageUploader = new ImageUploader('/fakedir', $this->sluggerInterface, $this->fileSystem);
        parent::__construct();
    }

    public function testGetTargetDirectory()
    {
        $result = $this->imageUploader->getTargetDirectory();
        $this->assertEquals('/fakedir', $result);
    }

    public function testSetTargetDirectory()
    {
       $this->imageUploader->setTargetDirectory('/newfakedir');
        $result = $this->imageUploader->getTargetDirectory();
        $this->assertEquals('/newfakedir', $result);
    }
}
