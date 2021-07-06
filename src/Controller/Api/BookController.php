<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends BaseApiController
{
    private BookRepository $bookRepository;
    private SerializerInterface $serializer;

    public function __construct(BookRepository $bookRepository, SerializerInterface $serializer)
    {
        $this->bookRepository = $bookRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route ("/api/books")
     */
    public function index()
    {
        $book = $this->bookRepository->findAll();

        return new Response(
            $this->serializer->serialize($book, 'json', ['groups' => 'show_book']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route ("/api/books/{id}")
     */
    public function show($id)
    {
        $book = $this->bookRepository->find($id);

        if (empty($book)) {
            return $this->notFoundJsonResponse('Book');
        }

        return new Response(
            $this->serializer->serialize($book, 'json', ['groups' => 'show_book', 'list_category']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
