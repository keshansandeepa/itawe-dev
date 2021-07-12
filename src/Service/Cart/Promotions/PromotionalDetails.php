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
    )
    {
        $this->appliedAmount = $appliedAmount;
        $this->isDiscountApplied = $isDiscountApplied;
        $this->discountName = $discountName;
        $this->remainingPrice = $remainingPrice;
    }

    /**
     * @return Money
     */
    public function getAppliedAmount(): Money
    {
        return $this->appliedAmount;
    }

    /**
     * @return string
     */
    public function getDiscountName(): string
    {
        return $this->discountName;
    }

    /**
     * @return Money
     */
    public function getRemainingPrice(): Money
    {
        return $this->remainingPrice;
    }

    /**
     * @return bool
     */
    public function isDiscountApplied(): bool
    {
        return $this->isDiscountApplied;
    }


}
