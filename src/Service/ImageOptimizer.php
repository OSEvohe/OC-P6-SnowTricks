<?php

namespace App\Service;

use App\Entity\User;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private const MAX_WIDTH = 200;
    private const MAX_HEIGHT = 200;

    private $imagine;
    /**
     * @var int
     */
    private $maxWidth;
    /**
     * @var int
     */
    private $maxHeight;

    /**
     * @return int
     */
    public function getMaxWidth(): int
    {
        return $this->maxWidth;
    }

    /**
     * @param int $maxWidth
     */
    public function setMaxWidth(int $maxWidth): void
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * @return int
     */
    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    /**
     * @param int $maxHeight
     */
    public function setMaxHeight(int $maxHeight): void
    {
        $this->maxHeight = $maxHeight;
    }

    public function __construct(int $maxWidth = 200, int $maxHeight = 200)
    {
        $this->imagine = new Imagine();
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    public function resize(string $filename): void
    {
        list($iwidth, $iheight) = getimagesize($filename);
        $ratio = $iwidth / $iheight;
        $width = $this->getMaxWidth();
        $height = $this->getMaxHeight();
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }

    public function resizeAvatar(User $user, ImageUploader $imageUploader){
        $filename = $imageUploader->getTargetDirectory().'/'.$user->getPhoto();
        $this->resize($filename);
    }
}