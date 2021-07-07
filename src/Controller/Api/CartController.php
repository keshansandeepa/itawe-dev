<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Manager\BookCartManager;
use App\Manager\CartManager;
use App\Repository\BookCartRepository;
use App\Repository\BookRepository;
use App\Service\Cart\CartService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends BaseApiController
{
    private SerializerInterface $serializer;
    private BookCartManager $bookCartManager;
    private CartService $cartService;
    private BookCartRepository $bookCartRepository;


    public function __construct(SerializerInterface $serializer, BookCartManager $bookCartManager, CartService $cartService, BookCartRepository $bookCartRepository)
    {
        $this->serializer = $serializer;
        $this->bookCartManager = $bookCartManager;
        $this->cartService = $cartService;
        $this->bookCartRepository = $bookCartRepository;
    }

    /**
     * @Route ("/api/cart", methods={"GET"})
     */
    public function index()
    {
        $data = [
            'books' => $this->cartService->books(),
            'meta' => [
                'empty' => $this->cartService->isEmpty(),
                'subtotal' => $this->cartService->subTotal(),
                'totalPrice' => $this->cartService->total()->formatted(),
            ],
        ];

        return new Response(
            $this->serializer->serialize($data, 'json', ['groups' => 'cart:index']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route ("/api/cart", methods={"POST"})
     */
    public function post(Request $request, BookRepository $bookRepository, CartManager $cartManager)
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $cartStorePayload = $this->cartService->getStorePayload($request->toArray()['books'], $bookRepository);

            $cartUser = $cartManager->findOrAddUserCart($this->getUser());

            $this->bookCartManager->save($cartStorePayload, $cartUser);

            $this->getDoctrine()->getConnection()->commit();

            return new Response(
                $this->serializer->serialize(['message' => 'Success'], 'json'),
                Response::HTTP_CREATED,
                ['Content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();

            return new Response(
                $this->serializer->serialize(['message' => 'Error'], 'json'),
                $exception->getCode(),
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route ("/api/carts/book/{id}", methods={"PUT"})
     */
    public function put(Request $request, Book $book, BookCartManager $bookCartManager)
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $requestArray = $request->toArray();

            $bookCart = $this->bookCartRepository->findBookCart($book, $this->getUser()->getCart());

            if (empty($bookCart)) {
                return new Response(
                    $this->serializer->serialize(['message' => 'Not Found'], 'json'),
                    Response::HTTP_NOT_FOUND,
                    ['Content-type' => 'application/json']
                );
            }

            $bookCartManager->update($bookCart, $requestArray['quantity']);

            $this->getDoctrine()->getConnection()->commit();

            return new Response(
                $this->serializer->serialize(['message' => 'Cart updated successfully'], 'json'),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();

            return new Response(
                $this->serializer->serialize(['message' => 'Error'], 'json'),
                $exception->getCode(),
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route ("/api/carts/book/{id}", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book)
    {
        $bookCart = $this->bookCartRepository->findBookCart($book, $this->getUser()->getCart());
    }
}
