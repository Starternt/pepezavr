<?php

declare(strict_types=1);

namespace App\Post\Service;

use App\Post\Entity\Post;
use App\User\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PostService
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function create(Post $post, User $user): Post
    {
        $post->setCreatedBy($user);

        // $this->eventDispatcher->dispatch(new CreatePostEvent($post), CreatePostEvent::NAME); TODO

        return $post;
    }
}
