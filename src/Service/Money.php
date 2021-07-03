<?php


namespace App\Service;


class Money
{
    private string $amount;

    public function __construct(string  $amount)
    {
        $this->amount = $amount;
    }

    public function formatted()
    {
        return $this->amount;
    }
}