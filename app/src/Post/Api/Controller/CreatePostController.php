<?php

declare(strict_types=1);

namespace App\Post\Api\Controller;

use App\Post\Entity\Post;
use App\User\Entity\User;

class CreatePostController extends AbstractPostController
{
    public function __invoke(Post $data): Post
    {
        dump(21); exit();
        /** @var User $user */
        $user = $this->getUser();

        $this->postService->create($data, $user);

        return $data;
    }
}
