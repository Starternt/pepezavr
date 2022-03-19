<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\AccessToken;
use App\Auth\Event\CreateAccessTokenEvent;
use App\User\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AccessTokenManager
{
    private const DEFAULT_LIFETIME = 86400;

    private EventDispatcherInterface $eventDispatcher;
    private int $tokenLifeTime;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        int $tokenLifeTime = self::DEFAULT_LIFETIME
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenLifeTime = $tokenLifeTime;
    }

    public function create(User $user): AccessToken
    {
        $accessToken = new AccessToken($user);
        $accessToken->setExpiredAfter($this->tokenLifeTime);

        $this->eventDispatcher->dispatch(new CreateAccessTokenEvent($accessToken), CreateAccessTokenEvent::NAME);

        return $accessToken;
    }
}
