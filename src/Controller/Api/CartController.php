<?php

namespace App\Controller\Api;

use App\Service\Cart\CartService;
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
     * @Route ("/api/carts", methods={"GET"})
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
}
