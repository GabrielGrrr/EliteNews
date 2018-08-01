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
     * @ORM\Column(name="weight", type="integer", options={"default" : 0})
     * @Assert\Choice(callback="getEnumWeight", message="L'importance de l'article ne correspond pas (ceci ne devrait pas arriver).")
     */
    private $weight;

    /**
     * @ORM\Column(name="removed", type="boolean", options={"default" : 0})
     */
    private $removed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(name="viewcount", type="integer", options={"default" : 0})
     */
    private $viewcount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Thread", mappedBy="article", cascade={"persist", "remove"})
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : "Autres"})
     * @Assert\Choice(callback="getEnumCategories", message="La catégorie envoyée n'est pas valide (ceci ne devrait pas arriver).")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $image;

    public function getId()
    {
        return $this->id;
    }

    public static function getEnumCategories()
    {
        return array('IT', 'Psychologie', 'Sociologie', 'Neurologie', 'Cosmologie', 'Physique', 'Sciences', 
        'Philosophie', 'Cinéma', 'Littérature', 'Arts', 'Politique', 'Autres');
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

        // set (or unset) the owning side of the relation if necessary
        $newArticle = $thread === null ? null : $this;
        if ($newArticle !== $thread->getArticle()) {
            $thread->setArticle($newArticle);
        }

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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
}
