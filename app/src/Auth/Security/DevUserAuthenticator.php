<?php

declare(strict_types=1);

namespace App\Auth\Security;

use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DevUserAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $em;
    private ParameterBagInterface $parameterBag;

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $parameterBag
    ) {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return
            'dev' === $this->parameterBag->get('kernel.environment')
            && $request->headers->has('userId')
        ;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $userId = $request->headers->get('userId');
        if (null === $userId) {
            throw new CustomUserMessageAuthenticationException('No user Id provided');
        }

        $user = $this->em->find(User::class, $userId);

        return new SelfValidatingPassport(new UserBadge((string) $user?->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
