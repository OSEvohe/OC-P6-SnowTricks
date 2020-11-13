<?php


namespace App\Service;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class ManageTrick
{
    /** @var ImageUploader */
    private $imageUploader;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Security */
    private $security;
    /**
     * @var YoutubeHelper
     */
    private $youtubeHelper;

    /**
     * ManageTrickDatabase constructor.
     * @param ImageUploader $imageUploader
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param YoutubeHelper $youtubeHelper
     */
    public function __construct(ImageUploader $imageUploader, EntityManagerInterface $em, Security $security, YoutubeHelper $youtubeHelper)
    {
        $this->imageUploader = $imageUploader;
        $this->em = $em;
        $this->security = $security;
        $this->youtubeHelper = $youtubeHelper;
    }


    /**
     * @param Trick $trick
     * @param FormInterface $form
     * @param ImageUploader $imageUploader
     * @return void
     */
    public function addUploadedTrickMediaImage(Trick $trick, FormInterface $form, ImageUploader $imageUploader)
    {
        $uploadedFile = $form->get('image')->getData();
        if ($uploadedFile) {
            /** @var TrickMedia $trickMedia */
            $trickMedia = $form->getData();
            $trickMedia->setContent($imageUploader->upload($uploadedFile));
            $trick->addTrickMedium($trickMedia);
        }
    }

    public function update(Trick $trick)
    {
        $trick->addContributor($this->security->getUser());
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
        /** @var Trick $trick */
        $trick = $form->getData();

        /** @var TrickMedia[] $collectionOfImage */
        $collectionOfImage = $form->get('trickMedia');

        /** Process additional TrickMedia */
        foreach ($collectionOfImage as $trickMedia) {
            if ($trickMedia->getData()->getType() == TrickMedia::MEDIA_TYPE_IMAGE) {
                $this->addUploadedTrickMediaImage($trick, $trickMedia, $imageUploader);
            } else {
                $trick->addTrickMedium($trickMedia->getData());
            }
        }
    }

    public function deleteImage(TrickMedia $media){

    }
}