<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6, max=255, minMessage = "Un titre de moins de {{ limit }} caractères risque de paraître un peu obscur ...",
     * maxMessage ="Le nombre de caractères maximum est de {{ limit }}")
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=25, minMessage = "Un article de moins de {{ limit }} caractères ? On est pas sur twitter ...")
     */
    private $content;

    /**
     * @ORM\Column(name="weight", type="integer", options={"default" : 0}, nullable=true)
     * @Assert\Choice(callback="getEnumWeight", message="L'importance de l'article ne correspond pas (ceci ne devrait pas arriver).")
     */
    private $weight;

    /**
     * @ORM\Column(name="removed", type="boolean", options={"default" : 0}, nullable=true)
     */
    private $removed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(name="viewcount", type="integer", options={"default" : 0}, nullable=true)
     */
    private $viewcount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Thread", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    public function getId()
    {
        return $this->id;
    }

    public function __toString() {
        return $this->titre;
    }
    
    public static function getEnumWeight()
    {
        return array(0, 1, 2);
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getRemoved(): ?bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getViewcount(): ?int
    {
        return $this->viewcount;
    }

    public function setViewcount(int $viewcount): self
    {
        $this->viewcount = $viewcount;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
