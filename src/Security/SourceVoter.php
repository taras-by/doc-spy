<?php


namespace App\Security;

use App\Entity\Source;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SourceVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Source) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Source $source
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $source, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($source, $user);
            case self::EDIT:
                return $this->canEdit($source, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Source $source, $user)
    {
        if ($user instanceof User) {
            if ($user->isAdmin()) {
                return true;
            }

            $owner = $source->getCreatedBy();
            if ($source->isPrivate() && $owner && $owner === $user && $source->isEnabled()) {
                return true;
            }
        }

        if ($source->isVisibleToEveryone() && $source->isEnabled()) {
            return true;
        }

        return false;
    }

    private function canEdit(Source $source, $user)
    {
        return false;
    }
}