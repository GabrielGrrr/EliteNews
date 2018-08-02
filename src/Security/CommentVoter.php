<?php
// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class CommentVoter extends Voter
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
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Comment) {
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

        // you know $subject is an Comment object, thanks to supports
        /** @var Comment $post */
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

    //Pas de limite d'accès en lecture pour les commentaires pour l'instant, à l'avenir réfléchir à protéger certains threads / forums sections
    private function canView(Comment $post, User $user, TokenInterface $token)
    {
        return true;
    }

    //Plsr différents voters dont un RoleVoter peuvent aussi directement être implémenté dans le controller pour y centraliser le paramètre des critères et le type de consensus attendu
    private function canEdit(Comment $post, User $user, TokenInterface $token)
    {
        return ($user === $post->getAuthor() || $this->decisionManager->decide($token, array('ROLE_MODERATOR')));
    }

    private function canSend(Comment $post, User $user, TokenInterface $token)
    {
        return ($this->decisionManager->decide($token, array('ROLE_USER')));
    }
}

?>