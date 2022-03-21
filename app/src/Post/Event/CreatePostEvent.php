<?php

declare(strict_types=1);

namespace App\Post\Event;

class CreatePostEvent extends PostEvent
{
    public const NAME = 'techRequest.cancel';
}
