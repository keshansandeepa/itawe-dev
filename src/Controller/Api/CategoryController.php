<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route ("/api/categories", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return new Response(
            $this->serializer->serialize($categoryRepository->findAll(), 'json', ['groups' => 'show_book']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
