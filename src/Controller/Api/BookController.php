<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends BaseApiController
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
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
