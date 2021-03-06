<?php

namespace App\Manager;

use App\Entity\BookCart;
use App\Entity\Cart;
use App\Entity\Coupon;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $user
     */
    public function findOrAddUserCart($user): Cart
    {
        $userCart = $user->getCart();

        if (empty($user->getCart())) {
            $newCart = new Cart();
            $newCart->setUser($user);
            $this->entityManager->persist($newCart);
            $this->entityManager->flush();

            return $newCart;
        }
        $this->entityManager->flush();

        return $userCart;
    }

    public function deleteBook(Cart $cart, BookCart $book)
    {
        $cart->removeBook($book);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function addCouponCode(Cart $cart, Coupon $coupon)
    {
        $cart->setCoupon($coupon);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function removeCouponCode(Cart $cart, Coupon $coupon)
    {
        $coupon->removeCart($cart);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
