<?php

namespace App\Service\Cart;

use App\Service\Money;

class CartDiscountServiceDetails
{
    private Money $appliedTotal;
    private Money $remainingTotal;

    public function __construct(Money $remainingTotal, Money $appliedTotal)
    {
        $this->remainingTotal = $remainingTotal;
        $this->appliedTotal = $appliedTotal;
    }

    public function getAppliedTotal(): Money
    {
        return $this->appliedTotal;
    }

    public function getRemainingTotal(): Money
    {
        return $this->remainingTotal;
    }
}
