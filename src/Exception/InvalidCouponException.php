<?php

namespace App\Exception;

use Exception;

class InvalidCouponException extends Exception
{
    protected $message = 'Invalid coupon code';
    protected $code = '404';

    public function report(): bool
    {
        return false;
    }

    public function render(): string
    {
        return $this->message;
    }
}
