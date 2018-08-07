<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumRepository")
 */
class Forum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Forum", mappedBy="parent")
     */
    private $subforums;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Forum", inversedBy="subforums")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Thread", mappedBy="forum")
     */
    private $threads;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNewsForum;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRootForum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    public function __construct()
    {
        $this->subforums = new ArrayCollection();
        $this->threads = new ArrayCollection();
    }

    public function __toString() {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): Forum
    {
        return $this->parent;
    }

    public function setParent(Forum $parent): self
    {
        if (!isset($this->parent)) {
            $this->parent = $parent;
        }

        return $this;
    }

    /**
     * @return Collection|Forum[]
     */
    public function getSubforums(): Collection
    {
        return $this->subforums;
    }

    public function addSubforum(Forum $subforum): self
    {
        if (!$this->subforums->contains($subforum)) {
            $this->subforums[] = $subforum;
            $subforum->setParent($this);
        }

        return $this;
    }

    public function removeSubforum(Forum $subforum): self
    {
        if ($this->subforums->contains($subforum)) {
            $this->subforums->removeElement($subforum);
            // set the owning side to null (unless already changed)
            if ($subforum->getParent() === $this) {
                $subforum->setParent(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|Thread[]
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread): self
    {
        if (!$this->threads->contains($thread)) {
            $this->threads[] = $thread;
            $thread->setForum($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread): self
    {
        if ($this->threads->contains($thread)) {
            $this->threads->removeElement($thread);
            // set the owning side to null (unless already changed)
            if ($thread->getForum() === $this) {
                $thread->setForum(null);
            }
        }

        return $this;
    }

    public function getIsNewsForum(): ?bool
    {
        return $this->isNewsForum;
    }

    public function setIsNewsForum(?bool $isNewsForum): self
    {
        $this->isNewsForum = $isNewsForum;

        return $this;
    }

    public function getIsRootForum(): ?bool
    {
        return $this->isRootForum;
    }

    public function setIsRootForum(?bool $isRootForum): self
    {
        $this->isRootForum = $isRootForum;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}
