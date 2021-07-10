<?php

namespace App\Service\Cart;

use App\Repository\BookRepository;

interface CartInterface
{
    public function getBooks();

    public function getTotal();

    public function isEmpty();

    public function getSubTotal();

    public function getBooksTotal();

    public function getStorePayload(array $books, BookRepository $bookRepository);
}
