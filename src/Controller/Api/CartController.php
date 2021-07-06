<?php


namespace App\Controller\Api;


use Symfony\Component\Routing\Annotation\Route;

class CartController extends BaseApiController
{
    /**
     * @Route ("/api/books/{id}")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

    }
}