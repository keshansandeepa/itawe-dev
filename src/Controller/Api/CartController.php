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
    public function post(
        Request $request,
        BookCartManager $bookCartManager,
        CartService $cartService,
        BookRepository $bookRepository,
        CartManager $cartManager
    ) {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $cartStorePayload = $cartService->getStorePayload($request->toArray()['books'], $bookRepository);

            $cartUser = $cartManager->findOrAddUserCart($this->getUser());

            $bookCartManager->save($cartStorePayload, $cartUser);

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
    public function put(Request $request, Book $book, BookCartRepository $bookCartRepository, BookCartManager $bookCartManager)
    {

        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $requestArray = $request->toArray();
            

            $bookCart = $bookCartRepository->findBookCart($book, $this->getUser()->getCart());

           
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
}
