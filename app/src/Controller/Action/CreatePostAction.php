<?php

declare(strict_types=1);

namespace App\Controller\Action;

use App\Entity\Post;
use App\Entity\User;
use App\Service\PostService;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreatePostAction extends AbstractController
{
    public function __construct(private PostService $postService, private ValidatorInterface $validator, private FilesystemOperator $usersStorage)
    {
    }

    public function __invoke(Post $data): Post
    {
        dump(3423); exit();
        /** @var User $user */
        $user = $this->getUser();

        $this->postService->create($data, $user);

        $this->validator->validate($data);

        return $data;
    }
}
