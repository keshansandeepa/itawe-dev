<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Manager\BookCartManager;
use App\Manager\CartManager;
use App\Repository\BookCartRepository;
use App\Repository\BookRepository;
use App\Service\Cart\CartService;
use App\Service\Coupon\CouponService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractController
{
    private SerializerInterface $serializer;
    private BookCartManager $bookCartManager;
    private CartService $cartService;
    private BookCartRepository $bookCartRepository;
    private CartManager $cartManager;
    private CouponService $couponService;

    public function __construct(
        SerializerInterface $serializer,
        BookCartManager $bookCartManager,
        CartService $cartService,
        BookCartRepository $bookCartRepository,
        CartManager $cartManager,
        CouponService $couponService
    ) {
        $this->serializer = $serializer;
        $this->bookCartManager = $bookCartManager;
        $this->cartService = $cartService;
        $this->bookCartRepository = $bookCartRepository;
        $this->cartManager = $cartManager;
        $this->couponService = $couponService;
    }

    /**
     * @Route ("/api/cart", methods={"GET"})
     */
    public function index(): Response
    {
        $data = [
            'books' => $this->cartService->getBooks(),
            'meta' => [
                'empty' => $this->cartService->isEmpty(),
                'itemsTotalPrice' => $this->cartService->getBooksTotal()->formatted(),
                'discountTotal' => $this->cartService->getCartDiscountTotal()->formatted(),
                'couponCode' => $this->cartService->getCouponDetails()->getCouponCode(),
                'appliedCouponTotal' => $this->cartService->getCouponDetails()->getAppliedAmount()->formatted(),
                'subtotal' => $this->cartService->getSubTotal()->formatted(),
                'totalPrice' => $this->cartService->getTotal()->formatted(),
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
    public function post(Request $request, BookRepository $bookRepository): Response
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $cartStorePayload = $this->cartService->getStorePayload($request->toArray()['books'], $bookRepository);

            $cartUser = $this->cartManager->findOrAddUserCart($this->getUser());

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
                $this->serializer->serialize(['message' => $exception->getMessage()], 'json'),
                500,
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route ("/api/carts/book/{id}", methods={"PUT"})
     */
    public function put(Request $request, Book $book): Response
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $requestArray = $request->toArray();

            $bookCart = $this->bookCartRepository->findBookCart($book, $this->getUser()->getCart());

            if (empty($bookCart)) {
                return new Response(
                    $this->serializer->serialize(['message' => 'Not found'], 'json'),
                    Response::HTTP_NOT_FOUND,
                    ['Content-type' => 'application/json']
                );
            }

            $this->bookCartManager->update($bookCart, $requestArray['quantity']);

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
    public function delete(Request $request, Book $book): Response
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $bookCart = $this->bookCartRepository->findBookCart($book, $this->getUser()->getCart());
            $this->cartManager->deleteBook($this->getUser()->getCart(), $bookCart);
            $this->getDoctrine()->getConnection()->commit();

            return new Response(
                $this->serializer->serialize(['message' => 'Success'], 'json'),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();

            return new Response(
                $this->serializer->serialize(['message' => 'Error'], 'json'),
                500,
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route ("/api/cart/coupon", methods={"POST"})
     */
    public function addCoupon(Request $request): Response
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $this->couponService->redeem($request->toArray()['code']);
            $this->getDoctrine()->getConnection()->commit();

            return new Response(
                $this->serializer->serialize(['message' => 'Coupon redeemed successfully'], 'json'),
                200,
                ['Content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();

            return new Response(
                $this->serializer->serialize(['message' => $exception->getMessage()], 'json'),
                0 == $exception->getCode() ? 500 : $exception->getCode(),
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route ("/api/cart/coupon/{code}", methods={"DELETE"})
     */
    public function deleteCoupon(Request $request, $code): Response
    {
        $this->getDoctrine()->getConnection()->beginTransaction();

        try {
            $this->couponService->removeCoupon($code);
            $this->getDoctrine()->getConnection()->commit();

            return new Response(
                $this->serializer->serialize(['message' => 'Coupon removed successfully'], 'json'),
                200,
                ['Content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();

            return new Response(
                $this->serializer->serialize(['message' => $exception->getMessage()], 'json'),
                0 == $exception->getCode() ? 500 : $exception->getCode(),
                ['Content-type' => 'application/json']
            );
        }
    }
}
