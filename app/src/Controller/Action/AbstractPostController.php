<?php

declare(strict_types=1);

namespace App\Controller\Action;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractPostController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected PostService $postService
    ) {
    }
}
