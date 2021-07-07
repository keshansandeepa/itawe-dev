<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Repository\BookCartRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    public function __construct(EntityManagerInterface $entityManager, BookCartRepository $bookCartRepository)
    {
        $this->entityManager = $entityManager;

        $this->bookCartRepository = $bookCartRepository;
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
}
