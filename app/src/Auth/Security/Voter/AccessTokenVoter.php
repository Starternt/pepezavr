<?php

declare(strict_types=1);

namespace App\Auth\Security\Voter;

use App\Auth\Entity\AccessToken;
use App\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccessTokenVoter extends Voter
{
    const ATTRIBUTE_DELETE = 'auth.delete_token';

    const ATTRIBUTES = [
        self::ATTRIBUTE_DELETE,
    ];

    protected function supports(string $attribute, $subject): bool
    {
        if (null !== $subject && !$subject instanceof AccessToken) {
            return false;
        }

        return \in_array(strtolower($attribute), self::ATTRIBUTES, true);
    }

    /**
     * @param null|AccessToken $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return match (strtolower($attribute)) {
            self::ATTRIBUTE_DELETE => $this->voteDelete($subject, $token),
            default => throw new \InvalidArgumentException(sprintf('Attribute [%s] is not supported', $attribute))
        };
    }

    private function voteDelete(?AccessToken $subject, TokenInterface $token): bool
    {
        if (null === $subject) {
            return false;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $subject->getUser()->getId() === $user->getId();
    }
}
