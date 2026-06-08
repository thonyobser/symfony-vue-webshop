<?php

namespace App\Shared\Infrastructure\Storage;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StorageController extends AbstractController
{
    #[Route('/storage/images/{path}', name: 'app_storage_image', requirements: ['path' => '.+'], methods: ['GET'])]
    public function image(string $path, Storage $storage): Response
    {
        try {
            $object = $storage->read($path);
        } catch (RuntimeException) {
            throw $this->createNotFoundException();
        }

        return new Response($object->content(), Response::HTTP_OK, [
            'Content-Type' => $object->contentType(),
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
