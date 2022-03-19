<?php

declare(strict_types=1);

namespace App\Auth\Security;

use App\User\Repository\ApiUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ApiUserTokenAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private const HEADER_NAME = 'token';

    private ApiUserRepository $apiUserRepository;

    public function __construct(ApiUserRepository $apiUserRepository)
    {
        $this->apiUserRepository = $apiUserRepository;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has(self::HEADER_NAME);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $id = (string) $request->headers->get(self::HEADER_NAME);
        $user = $this->apiUserRepository->findOneBy(['apiKey' => $id]);
        if (null === $user) {
            throw new AuthenticationException('Access token not found');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }
}
