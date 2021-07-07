<?php

namespace App\Manager;

use App\Entity\BookCart;
use App\Entity\User;
use App\Repository\BookCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

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

    public function save(ArrayCollection $cartStorePayload, User $user): void
    {
        $cartStorePayload->map(function ($bookCollection) use ($user) {
            $this->findOrAddBookCart($bookCollection['book'], $user, $bookCollection['quantity']);
        });
        $this->entityManager->flush();
    }

    public function update(BookCart $bookCart, int $quantity)
    {
        $bookCart->setQuantity($quantity);
        $this->entityManager->persist($bookCart);
        $this->entityManager->flush();
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
        $bookCart = $this->bookCartRepository->findBookCart($bookId, $userCart);
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
}
