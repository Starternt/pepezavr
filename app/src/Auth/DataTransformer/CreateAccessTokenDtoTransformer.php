<?php

declare(strict_types=1);

namespace App\Auth\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Auth\DTO\CreateTokenDTO;
use App\Auth\Entity\AccessToken;
use App\Auth\Service\AccessTokenManager;
use App\User\Entity\User;
use Symfony\Component\Security\Core\Security;

class CreateAccessTokenDtoTransformer implements DataTransformerInterface
{
    private Security $security;
    private AccessTokenManager $tokenManager;

    public function __construct(Security $security, AccessTokenManager $tokenManager)
    {
        $this->security = $security;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param CreateTokenDTO $object
     */
    public function transform($object, string $to, array $context = []): AccessToken
    {
        /** @var null|User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            throw new \RuntimeException('User is not authenticated');
        }

        if ($user->isDeleted()) {
            throw new \RuntimeException('User deleted');
        }

        return $this->tokenManager->create($user);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof AccessToken) {
            return false;
        }

        return AccessToken::class === $to && CreateTokenDTO::class === ($context['input']['class'] ?? null);
    }
}
