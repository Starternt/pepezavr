<?php

declare(strict_types=1);

namespace App\Post\Api\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Post\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractPostController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected PostService $postService
    ) {
    }
}
