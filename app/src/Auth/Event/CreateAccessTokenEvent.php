<?php

declare(strict_types=1);

namespace App\Auth\Event;

use App\Auth\Entity\AccessToken;
use Symfony\Contracts\EventDispatcher\Event;

class CreateAccessTokenEvent extends Event
{
    public const NAME = 'auth.accessToken.access';

    private AccessToken $token;

    public function __construct(AccessToken $token)
    {
        $this->token = $token;
    }

    public function getToken(): AccessToken
    {
        return $this->token;
    }
}
