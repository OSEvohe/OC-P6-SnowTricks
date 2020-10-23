<?php


namespace App\Service;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class ManageTrickDatabase
{
    /** @var ImageUploader */
    private $imageUploader;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Security */
    private $security;

    /**
     * ManageTrickDatabase constructor.
     * @param ImageUploader $imageUploader
     * @param EntityManagerInterface $em
     * @param Security $security
     */
    public function __construct(ImageUploader $imageUploader, EntityManagerInterface $em, Security $security)
    {
        $this->imageUploader = $imageUploader;
        $this->em = $em;
        $this->security = $security;
    }


    /**
     * @param Trick $trick
     * @param FormInterface $trickMediaImageForm
     * @param ImageUploader $imageUploader
     * @return void
     */
    public function addUploadedTrickMediaImage(Trick $trick, FormInterface $trickMediaImageForm, ImageUploader $imageUploader)
    {
        $uploadedFile = $trickMediaImageForm->get('content')->getData();
        if ($uploadedFile) {
            /** @var TrickMedia $trickMedia */
            $trickMedia = $trickMediaImageForm->getData();
            $trickMedia->setContent($imageUploader->upload($uploadedFile));
            $trick->addTrickMedium($trickMedia);
        }
    }

    public function trick(FormInterface $form)
    {
        /** @var Trick $trick */
        $trick = $form->getData();

        /** set Owner for new trick */
        if (NULL === $trick->getUser()) {
            $trick->setUser($this->security->getUser());
        }

        $this->em->persist($trick);
        $this->em->flush();
    }

    public function addCover(FormInterface $form, ImageUploader $imageUploader)
    {
        $trick = $form->getData();
        /** Process cover image */
        $this->addUploadedTrickMediaImage($trick, $form->get('cover'), $imageUploader);
    }

    public function addTrickMediaCollection(FormInterface $form, ImageUploader $imageUploader)
    {
        $trick = $form->getData();

        /** Process additional TrickMedia */
        $collectionOfImage = $form->get('trickMediaPicture');
        foreach ($collectionOfImage as $trickMedia) {
            $this->addUploadedTrickMediaImage($trick, $trickMedia, $imageUploader);
        }

        $collectionOfVideo = $form->get('trickMediaVideo');
        foreach ($collectionOfVideo as $trickMedia) {
            $trick->addTrickMedium($trickMedia->getData());
        }
    }

}