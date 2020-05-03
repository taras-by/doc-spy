<?php


namespace App\Security;

use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TagVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Tag) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Tag $tag
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $tag, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($tag, $user);
            case self::EDIT:
                return $this->canEdit($tag, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Tag $tag, $user)
    {
        if ($user instanceof User && $user->isAdmin()) {
            return true;
        }

        if ($tag->isEnabled()) {
            return true;
        }

        return false;
    }

    private function canEdit(Tag $tag, $user)
    {
        return false;
    }
}
