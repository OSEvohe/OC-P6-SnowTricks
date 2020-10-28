<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 */
class Trick
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=TrickGroup::class, inversedBy="tricks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $trickGroup;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\OneToMany(targetEntity=TrickMedia::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $trickMedia;

    /**
     * @ORM\OneToOne(targetEntity=TrickMedia::class, inversedBy="cover", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $cover;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="contributions")
     * @ORM\JoinTable(name="trick_contributors")
     */
    private $contributors;


    public function __construct()
    {
        $this->trickMedia = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->contributors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTrickGroup(): ?TrickGroup
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(?TrickGroup $TrickGroup): self
    {
        $this->trickGroup = $TrickGroup;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|TrickMedia[]
     */
    public function getTrickMedia(): Collection
    {
        return $this->trickMedia;
    }

    public function addTrickMedium(TrickMedia $trickMedium): self
    {
        if (!$this->trickMedia->contains($trickMedium)) {
            $this->trickMedia[] = $trickMedium;
            $trickMedium->setTrick($this);
        }

        return $this;
    }

    public function removeTrickMedium(TrickMedia $trickMedium): self
    {
        if ($this->trickMedia->contains($trickMedium)) {
            $this->trickMedia->removeElement($trickMedium);
            // set the owning side to null (unless already changed)
            if ($trickMedium->getTrick() === $this) {
                $trickMedium->setTrick(null);
            }
        }

        return $this;
    }

    public function getCover(): ?TrickMedia
    {
        return $this->cover;
    }

    public function setCover(?TrickMedia $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getContributors(): Collection
    {
        return $this->contributors;
    }

    public function addContributor(User $contributor): self
    {
        if (!$this->contributors->contains($contributor)) {
            $this->contributors[] = $contributor;
            $contributor->addContribution($this);
        }

        return $this;
    }

    public function removeContributor(User $contributor): self
    {
        if ($this->contributors->contains($contributor)) {
            $this->contributors->removeElement($contributor);
            $contributor->removeContribution($this);
        }

        return $this;
    }
}
