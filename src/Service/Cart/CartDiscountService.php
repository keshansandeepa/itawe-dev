<?php

namespace App\Service\Cart;

use App\Repository\CategoryRepository;
use App\Service\Cart\Promotions\CategoryBookPromotion;
use App\Service\Cart\Promotions\ChildrenBookPromotion;
use App\Service\Money;

class CartDiscountService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function apply(Money $total, $cartPayload): CartDiscountServiceDetails
    {
        $childrenBookPromotion = (new ChildrenBookPromotion($total, $cartPayload))->apply();
        $categoryPromotion = (new CategoryBookPromotion($childrenBookPromotion->getRemainingPrice(), $cartPayload, $this->categoryRepository))->apply();

        return new CartDiscountServiceDetails(
            $categoryPromotion->getRemainingPrice(),
            $childrenBookPromotion->getAppliedAmount()->add($categoryPromotion->getAppliedAmount())
        );
    }
}
