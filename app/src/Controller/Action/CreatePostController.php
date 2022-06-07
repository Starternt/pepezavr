<?php

declare(strict_types=1);

namespace App\Controller\Action;

use App\Entity\Post;
use App\Entity\User;

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
