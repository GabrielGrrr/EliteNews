<?php
// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const SEND = 'send';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::SEND))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Article) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is an Article object, thanks to supports
        /** @var Article $post */
        $post = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($post, $user, $token);
            case self::EDIT:
                return $this->canEdit($post, $user, $token);
            case self::SEND:
                return $this->canSend($post, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    //Un article n'est jamais vraiment supprimé par sécurité, il est masqué à tous les users sauf spécifié ici, un bandeau devrait avertir l'user du statut de l'article dans la vue
    private function canView(Article $post, User $user, TokenInterface $token)
    {
        return (!$post->getRemoved() || $user === $post->getAuthor() || $this->decisionManager->decide($token, array('ROLE_ADMIN')));
    }

    //Plusieurs voteurs pourraient être implémentés pour séparer les compétences et implémenter pleinement le système de vote par consensus etc. Pas nécessaire ici.
    private function canEdit(Article $post, User $user, TokenInterface $token)
    {
        return ($user === $post->getAuthor() || $this->decisionManager->decide($token, array('ROLE_MODERATOR')));
    }

    private function canSend(Article $post, User $user, TokenInterface $token)
    {
        return ($this->decisionManager->decide($token, array('ROLE_PUBLISHER')));
    }
}

?>