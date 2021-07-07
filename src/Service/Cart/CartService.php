<?php

namespace App\Service\Cart;

use App\Repository\BookRepository;
use App\Service\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;

class CartService implements CartInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function total(): Money
    {
        return $this->booksTotal();
    }

    public function isEmpty(): bool
    {
        if (empty($this->security->getUser()->getCart())) {
            return true;
        }

        return false;
    }

    public function books()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return $this->security->getUser()->getCart()->getBooks();
    }

    public function subTotal()
    {
        return true;
    }

    public function booksTotal(): Money
    {
        $price = new Money(0);

        $this->books()->map(function ($item) use (&$price) {
            $price->add($item->getTotalPrice());
        });

        return $price;
    }

    public function getStorePayload(array $books, BookRepository $bookRepository): ArrayCollection
    {
        $requestBookCollection = new ArrayCollection($books);

        return $requestBookCollection->map(function ($bookCollection) use ($bookRepository) {
            return [
              'quantity' => $bookCollection['quantity'],
              'book' => $bookRepository->find($bookCollection['id']),
           ];
        });
    }

    public function updateStorePayload(array $books, BookRepository $bookRepository): ArrayCollection
    {
        $requestBookCollection = new ArrayCollection($books);
    }
}
