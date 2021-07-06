<?php


namespace App\Service\Cart;


interface CartInterface
{

    public function products();
    public function total();

}