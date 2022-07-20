<?php

declare(strict_types=1);

namespace App\Controller\Action;

use App\Entity\Post;
use Aws\S3\S3Client;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

// TODO delete this method. Created only for tests
final class CreateFileAction extends AbstractController
{
    public function __construct(private FilesystemOperator $usersStorage, private S3Client $s3Client, $min)
    {
    }

    public function __invoke(Request $request): Post
    {
        exit;
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        $path = 'uploads/'.$file->getClientOriginalName();
        $stream = fopen($file->getPathname(), 'r');

        // // The internal adapter
        // $adapter = new AwsS3V3Adapter(
        // // S3Client
        //     $this->s3Client,
        //     // Bucket name
        //     'my-first-bucket',
        //     // Optional path prefix
        //     '',
        //     // Visibility converter (League\Flysystem\AwsS3V3\VisibilityConverter)
        //     new PortableVisibilityConverter(
        //     // Optional default for directories
        //         Visibility::PUBLIC // or ::PRIVATE
        //     )
        // );
        //
        // $filesystem = new Filesystem($adapter);
        // dump($filesystem->fileExists('ZmlsZS5wZGY=')); exit();
        // $filesystem->writeStream(
        //     $path,
        //     $stream
        // );
        // dump(1); exit();

        // dump($this->usersStorage->fileExists('file.pdf')); exit();

        // $this->usersStorage->writeStream(
        //     $path,
        //     $stream
        // );
    }
}
