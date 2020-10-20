<?php


namespace App\Service;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

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


    /**
     * @param Trick $trick
     * @param FormInterface $trickMediaImageForm
     * @param ImageUploader $imageUploader
     * @return bool
     */
    public function addUploadedTrickMediaImage(Trick $trick, FormInterface $trickMediaImageForm, ImageUploader $imageUploader) {
        $uploadedFile = $trickMediaImageForm->get('content')->getData();
        if ($uploadedFile) {
            /** @var TrickMedia $trickMedia */
            $trickMedia = $trickMediaImageForm->getData();
            $trickMedia->setContent($imageUploader->upload($uploadedFile));
            $trick->addTrickMedium($trickMedia);
        }
    }

    public function addNewTrick(Trick $trick, FormInterface $form, ImageUploader $imageUploader)
    {
        /** Process cover image */
       $this->addUploadedTrickMediaImage($trick, $form->get('cover'), $imageUploader);

        /** Process additional TrickMedia */
        $collectionOfImage = $form->get('trickMediaPicture');
        foreach ($collectionOfImage as $trickMedia) {
            $this->addUploadedTrickMediaImage($trick, $trickMedia, $imageUploader);
        }

        $collectionOfVideo =$form->get('trickMediaVideo');
        foreach ($collectionOfVideo as $trickMedia) {
            $trick->addTrickMedium($trickMedia->getData());
        }
    }

}