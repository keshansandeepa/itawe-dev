<?php


namespace App\Controller\Api;


use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends BaseApiController
{

    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository  =$bookRepository;
    }

    /**
     * @Route ("/api/books")
     */
    public function index()
    {
        return new Response("test",200);
    }

    /**
     * @Route ("/api/books/{id}")
     */

    public function show($id)
    {
        $book = $this->bookRepository->find($id);

        if (empty($book)){
            return $this->notFoundJsonResponse('Book');
        }
        return $this->json([
           'id' => $book->getId(),
           'title' => $book->getTitle(),
           'slug' => $book->getSlug(),
           'description' => $book->getDescription(),
        ],200);
    }

}