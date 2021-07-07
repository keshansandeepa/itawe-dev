<?php

namespace App\Manager;

use App\Entity\BookCart;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOrAddUserCart($user): Cart
    {
        $userCart = $user->getCart();

        if (empty($user->getCart())) {
            $newCart = new Cart();
            $newCart->setUser($user);
            $this->entityManager->persist($newCart);

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
}
