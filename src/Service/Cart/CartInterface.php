<?php

namespace App\Service\Cart;

use App\Repository\BookRepository;

interface CartInterface
{
    public function books();

    public function total();

    public function isEmpty();

    public function subTotal();

    public function booksTotal();

    public function getStorePayload(array $books, BookRepository $bookRepository);
}
