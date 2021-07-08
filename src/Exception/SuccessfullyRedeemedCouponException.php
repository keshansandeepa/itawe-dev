<?php

namespace App\Exception;

use Exception;

class SuccessfullyRedeemedCouponException extends Exception
{
    protected $message = 'Coupon code redeemed Successfully';
    protected $code = '200';

    public function report(): bool
    {
        return false;
    }

    public function render(): string
    {
        return $this->message;
    }
}
