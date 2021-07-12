<?php

namespace App\Service\Cart\Promotions;

use App\Service\Money;

class PromotionalDetails
{
    private Money $appliedAmount;
    private string $discountName;
    private Money $remainingPrice;
    private bool $isDiscountApplied;

    public function __construct(
        Money $appliedAmount,
        bool $isDiscountApplied,
        string $discountName,
        Money $remainingPrice
    ) {
        $this->appliedAmount = $appliedAmount;
        $this->isDiscountApplied = $isDiscountApplied;
        $this->discountName = $discountName;
        $this->remainingPrice = $remainingPrice;
    }

    public function getAppliedAmount(): Money
    {
        return $this->appliedAmount;
    }

    public function getDiscountName(): string
    {
        return $this->discountName;
    }

    public function getRemainingPrice(): Money
    {
        return $this->remainingPrice;
    }

    public function isDiscountApplied(): bool
    {
        return $this->isDiscountApplied;
    }
}
