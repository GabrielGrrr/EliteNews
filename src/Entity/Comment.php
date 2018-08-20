<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="integer", options={"default" : 0}, nullable=true)
     */
    private $like_counter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thread", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __toString()
    {
        return $this->content;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent() : ? string
    {
        return $this->content;
    }

    public function setContent(string $content) : self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateCreation() : ? \DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation) : self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getLikeCounter() : ? int
    {
        return $this->like_counter;
    }

    public function setLikeCounter(int $like_counter) : self
    {
        $this->like_counter = $like_counter;

        return $this;
    }

    public function getThread() : ? Thread
    {
        return $this->thread;
    }

    public function setThread(? Thread $thread) : self
    {
        $this->thread = $thread;

        return $this;
    }

    public function getAuthor() : ? User
    {
        return $this->author;
    }

    public function setAuthor(? User $author) : self
    {
        $this->author = $author;

        return $this;
    }
}
