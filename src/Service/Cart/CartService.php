<?php

namespace App\Service\Cart;

use App\Service\Money;
use Symfony\Component\Security\Core\Security;

class CartService implements CartInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function total()
    {
        return $this->booksTotal();
    }

    public function isEmpty()
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

    public function booksTotal()
    {
        $price = new Money(0);
        foreach ($this->books() as $product) {
            $price->add($product->getTotalPrice());
        }

        return $price;
    }
}
