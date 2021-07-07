<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class BaseApiController extends AbstractController
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function notFoundJsonResponse($entity)
    {
        return $this->json([
            'message' => "{$entity} not found",
        ], 404);
    }
}
