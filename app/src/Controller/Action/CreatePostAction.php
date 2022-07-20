<?php

declare(strict_types=1);

namespace App\Controller\Action;

use App\Entity\Post;
use App\Entity\User;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreatePostAction extends AbstractController
{
    public function __construct(private PostService $postService, private ValidatorInterface $validator)
    {
    }

    public function __invoke(Post $data): Post
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->postService->create($data, $user);

        $this->validator->validate($data);

        return $data;
    }
}
