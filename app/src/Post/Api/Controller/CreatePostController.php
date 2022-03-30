<?php

declare(strict_types=1);

namespace App\Post\Api\Controller;

use App\Post\Entity\Post;
use App\User\Entity\User;

final class CreatePostController extends AbstractPostController
{
    public function __invoke(Post $data): Post
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->postService->create($data, $user);

        $this->validator->validate($data);

        return $data;
    }
}
