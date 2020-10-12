<?php

namespace App\Entity;

use App\Repository\TrickMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickMediaRepository::class)
 */
class TrickMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="trickMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="cover")
     */
    private $tricks;

    /**
     * @ORM\OneToOne(targetEntity=Trick::class, mappedBy="cover", cascade={"persist", "remove"})
     */
    private $cover;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setCover($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getCover() === $this) {
                $trick->setCover(null);
            }
        }

        return $this;
    }

    public function getCover(): ?Trick
    {
        return $this->cover;
    }

    public function setCover(?Trick $cover): self
    {
        $this->cover = $cover;

        // set (or unset) the owning side of the relation if necessary
        $newCover = null === $cover ? null : $this;
        if ($cover->getCover() !== $newCover) {
            $cover->setCover($newCover);
        }

        return $this;
    }
}
