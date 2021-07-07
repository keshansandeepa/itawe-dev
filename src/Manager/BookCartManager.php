<?php

namespace App\Manager;

use App\Entity\BookCart;
use App\Entity\Cart;
use App\Repository\BookCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BookCartManager
{
    private EntityManagerInterface $entityManager;
    private BookCartRepository $bookCartRepository;

    /**
     * BookCartManager constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, BookCartRepository $bookCartRepository)
    {
        $this->entityManager = $entityManager;

        $this->bookCartRepository = $bookCartRepository;
    }

    public function save(ArrayCollection $cartStorePayload, UserInterface $user): void
    {
        $userCart = $this->findOrAddUserCart($user);

        $cartStorePayload->map(function ($bookCollection) use ($userCart) {
            $this->findOrAddBookCart($bookCollection['book'], $userCart, $bookCollection['quantity']);
        });
        $this->entityManager->flush();
    }

    public function update(ArrayCollection $cartUpdatePayload)
    {
        $bookCart = $this->bookCartRepository->findBookCart($bookId, $userCart);
    }

    /**
     * @param $user
     */
    protected function findOrAddUserCart($user): Cart
    {
        $userCart = $user->getCart();

        if (empty($user->getCart())) {
            $newCart = new Cart();
            $newCart->setUser($user);
            $this->entityManager->persist($newCart);
            $this->entityManager->flush();

            return $newCart;
        }

        return $userCart;
    }

    /**
     * @param $bookId
     * @param $userCart
     * @param $quantity
     *
     * @return BookCart|int|mixed|string
     */
    protected function findOrAddBookCart($bookId, $userCart, $quantity): BookCart
    {
        $bookCart = $this->findBookCart($bookId, $userCart);
        if (empty($bookCart)) {
            $newBookCart = new BookCart();
            $newBookCart->setBook($bookId);
            $newBookCart->setCart($userCart);
            $newBookCart->setQuantity($quantity);
            $this->entityManager->persist($newBookCart);

            return $newBookCart;
        }

        $bookCart->setQuantity($quantity);

        $this->entityManager->persist($bookCart);

        return $bookCart;
    }

    protected function findBookCart($bookId, $userCart)
    {
        return $this->bookCartRepository->findBookCart($bookId, $userCart);
    }
}
