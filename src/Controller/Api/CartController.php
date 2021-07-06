<?php


namespace App\Controller\Api;


use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;

use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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
     *
     */
    public function index(CartService $cart)
    {
        $data = [
            'products' =>  $cart->products(),
            'totalPrice' => $cart->total()->formatted(),
            'Coupon' => '1100'
        ];
        return new Response(
            $this->serializer->serialize($data, 'json',['groups' => 'cart:index']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );

    }
}