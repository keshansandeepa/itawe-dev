<?php


namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseApiController extends AbstractController
{


    public function notFoundJsonResponse($entity)
    {
        return $this->json([
            "message" => "{$entity} not found"
        ],404);
    }
}