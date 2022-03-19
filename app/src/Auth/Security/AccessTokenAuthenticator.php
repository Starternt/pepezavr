<?php

declare(strict_types=1);

namespace App\Auth\Security;

use App\Auth\Repository\AccessTokenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AccessTokenAuthenticator extends AbstractAuthenticator
{
    private const HEADER_NAME = 'x-access-token';

    private AccessTokenRepository $tokenRepository;

    public function __construct(AccessTokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::HEADER_NAME);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $id = (string) $request->headers->get(self::HEADER_NAME);

        try {
            $accessToken = $this->tokenRepository->find($id);
        } catch (\Exception $e) {
            $accessToken = null;
        }

        if (null === $accessToken || false === $accessToken->isActive()) {
            throw new AuthenticationException('Access token is incorrect');
        }

        return new SelfValidatingPassport(new UserBadge($accessToken->getUser()->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }
}
