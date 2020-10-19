<?php


namespace App\Service;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use Doctrine\ORM\EntityManagerInterface;

class ManageTrickDatabase
{
    /** @var ImageUploader */
    private $imageUploader;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * ManageTrickDatabase constructor.
     * @param ImageUploader $imageUploader
     * @param EntityManagerInterface $em
     */
    public function __construct(ImageUploader $imageUploader, EntityManagerInterface $em)
    {
        $this->imageUploader = $imageUploader;
        $this->em = $em;
    }


    public function setCover(Trick $trick, TrickMedia $cover){
        $trick->getCover()->setContent('figure1');
        $trick->setCover($trick->getCover());
        $trick->getCover()->setTrick($trick);
    }


}