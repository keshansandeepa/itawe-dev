<?php

namespace App\Service\Coupon;

use App\Service\Money;

class CartCouponDetails
{
    private Money $appliedAmount;
    private string $couponCode;
    private Money $remainingTotal;

    public function __construct(Money $appliedAmount, string $couponCode, Money $remainingTotal)
    {
        $this->appliedAmount = $appliedAmount;
        $this->couponCode = $couponCode;
        $this->remainingTotal = $remainingTotal;
    }

    public function getAppliedAmount(): Money
    {
        return $this->appliedAmount;
    }

    public function getCouponCode(): string
    {
        return $this->couponCode;
    }

    public function getRemainingTotal(): Money
    {
        return $this->remainingTotal;
    }
}
