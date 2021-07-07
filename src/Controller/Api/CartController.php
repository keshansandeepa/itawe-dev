<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Manager\BookCartManager;
use App\Repository\BookRepository;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends BaseApiController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route ("/api/cart", methods={"GET"})
     */
    public function index(CartService $cart)
    {
        $data = [
            'books' => $cart->books(),
            'meta' => [
                'empty' => $cart->isEmpty(),
                'subtotal' => $cart->subTotal(),
                'totalPrice' => $cart->total()->formatted(),
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
    public function post(Request $request, BookCartManager $bookCartManager, CartService $cartService, BookRepository $bookRepository)
    {
        $cartStorePayload = $cartService->getStorePayload($request->toArray()['books'], $bookRepository);

        $bookCartManager->save($cartStorePayload, $this->getUser());

        return new Response(
            $this->serializer->serialize(['message' => 'Success'], 'json', ['groups' => 'cart:index']),
            Response::HTTP_CREATED,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route ("/api/carts/book/{id}", methods={"PUT"})
     */
    public function put(Request $request, Book $book)
    {
        dd('test');
        $cartStorePayload = $cartService->updateStorePayload($request->toArray());

        return new Response(
            $this->serializer->serialize(['message' => 'Success'], 'json', ['groups' => 'cart:index']),
            Response::HTTP_NO_CONTENT,
            ['Content-type' => 'application/json']
        );
    }
}
