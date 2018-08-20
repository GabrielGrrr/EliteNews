<?php

namespace App\Entity;

use App\Entity\Comment;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity( fields = {"email"},
 * message= "L'email ou le login indiqué est déjà utilisé")
 * @UniqueEntity( fields = {"login"},
 * message= "Le login demandé est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length( min=4,
     * minMessage = "Votre login doit faire au moins {{ limit }} caractères.")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min=8,
     * minMessage = "Votre mot de passe doit faire au moins {{ limit }} caractères.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email( mode = "strict",
     *     message = "Email '{{ value }}' invalide.",
     *     checkMX = true )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : "ROLE_USER"}, nullable=true)
     */
    private $user_role;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : "http://via.placeholder.com/90x90"}, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length( max=15,
     * maxMessage = "Votre (courte) description doit faire moins de {{ limit }} caractères.")
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=100,
     * maxMessage ="Le nombre de caractères maximum est de {{ limit }}")
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=20,
     * maxMessage ="Le nombre de caractères maximum est de {{ limit }}")
     */
    private $localisation;

    /**
     * @ORM\Column(type="integer", options={"default" : 0}, nullable=true)
     */
    private $moderation_status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0}, nullable=true)
     */
    private $newsletter_subscriber;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\IsTrue(message="Vous devez accepter nos conditions d'usage")
     */
    private $termsaccepted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_subscription;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Thread", mappedBy="author", orphanRemoval=false)
     */
    private $threads;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=false)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="author", orphanRemoval=false)
     */
    private $articles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->threads = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->user_role = 'ROLE_USER';
        $this->moderation_status = 0;
        $this->isActive = false;
    }

    public function __toString()
    {
        return $this->login;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin() : ? string
    {
        return $this->login;
    }

    public function getUserName() : ? string
    {
        return $this->email;
    }

    public function setLogin(string $login) : self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword() : ? string
    {
        return $this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    

    /*public function setConfirmPassword()
    {
        return $this->confirm_password;
    }*/

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserRole() : ? string
    {
        return $this->user_role;
    }

    public function setUserRole(string $user_role) : string
    {
        $this->user_role = $user_role;

        return $this;
    }

    public function getAvatar() : ? string
    {
        return $this->avatar;
    }

    public function setAvatar(? string $avatar) : self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSubtitle() : ? string
    {
        return $this->subtitle;
    }

    public function setSubtitle(? string $subtitle) : self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getSignature() : ? string
    {
        return $this->signature;
    }

    public function setSignature(? string $signature) : self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getLocalisation() : ? string
    {
        return $this->localisation;
    }

    public function setLocalisation(? string $localisation) : self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getModerationStatus() : ? int
    {
        return $this->moderation_status;
    }

    public function setModerationStatus(int $moderation_status) : self
    {
        $this->moderation_status = $moderation_status;

        return $this;
    }

    public function getDateInscription() : ? \DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription) : self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getTermsAccepted() : ? bool
    {
        return $this->termsaccepted;
    }

    public function setTermsAccepted(bool $termsaccepted) : self
    {
        $this->termsaccepted = $termsaccepted;

        return $this;
    }

    public function getIsActive() : ? bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive) : self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getNewsletterSubscriber() : ? bool
    {
        return $this->newsletter_subscriber;
    }

    public function setNewsletterSubscriber(bool $newsletter_subscriber) : self
    {
        $this->newsletter_subscriber = $newsletter_subscriber;

        return $this;
    }

    public function getDateSubscription() : ? \DateTimeInterface
    {
        return $this->date_subscription;
    }

    public function setDateSubscription(? \DateTimeInterface $date_subscription) : self
    {
        $this->date_subscription = $date_subscription;

        return $this;
    }

    public function eraseCredentials()
    {

    }

    public function getSalt()
    {

    }

    public function getRoles()
    {
        return [$this->user_role];
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,

            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,

            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    /**
     * @return Collection|Thread[]
     */
    public function getThreads() : Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread) : self
    {
        if (!$this->threads->contains($thread)) {
            $this->threads[] = $thread;
            $thread->setAuthor($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread) : self
    {
        if ($this->threads->contains($thread)) {
            $this->threads->removeElement($thread);
            // set the owning side to null (unless already changed)
            if ($thread->getAuthor() === $this) {
                $thread->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments() : Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment) : self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment) : self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles() : Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article) : self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article) : self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }
}
