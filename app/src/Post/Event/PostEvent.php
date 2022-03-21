<?php

declare(strict_types=1);

namespace App\Post\Event;

use App\Post\Entity\Post;
use Symfony\Contracts\EventDispatcher\Event;

abstract class PostEvent extends Event
{
    public function __construct(private Post $post)
    {
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
