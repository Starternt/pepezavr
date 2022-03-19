<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginController extends AbstractController
{
    /**
     * @Route("/login/{id}", name="auth_login")
     */
    public function indexAction(
        User $user,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        Request $request
    ): Response {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $tokenStorage->setToken($token);
        $session->set('_security_main', serialize($token));

        if ('json' === $request->getContentType()) {
            return new JsonResponse(['user_id' => $user->getId()]);
        }

        $referer = $request->headers->get('referer');
        $url = $referer ?? '/api/graphql';

        return $this->redirect($url);
    }
}
