<?php


namespace App\Service;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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


    public function setUploadedCover(Trick $trick, FormInterface $coverForm, ImageUploader $imageUploader): bool{
        $coverFile = $coverForm->get('content')->getData();
        if ($coverFile) {
            /** @var TrickMedia $cover */
            $cover = $coverForm->getData();
            $cover->setContent($imageUploader->upload($coverFile));
            $cover->setTrick($trick);
        } else {
            $coverForm->addError(new FormError('Veuillez choisir un fichier à uploader en tant qu\'image principale'));
            return false;
        }
        return true;
    }


}