<?php


namespace App\Exception;

use Exception;
class RedeemedCouponException extends Exception
{
    protected $message = 'Your cart already redeem a coupon code';
    protected $code = '422';

    public function report(): bool
    {
        return false;
    }

    public function render(): string
    {
        return $this->message;
    }

}