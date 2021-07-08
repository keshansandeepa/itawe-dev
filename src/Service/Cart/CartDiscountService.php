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

    public function apply(Money $total, $cartPayload): array
    {
        $childrenBookPromotion = (new ChildrenBookPromotion($total, $cartPayload))->apply();

        $categoryPromotion = (new CategoryBookPromotion($childrenBookPromotion['remainingPrice'], $cartPayload, $this->categoryRepository))->apply();

        return [
            'remainingTotal' => $categoryPromotion['remainingPrice'],
            'appliedDiscountTotal' => $childrenBookPromotion['appliedAmount']->add($categoryPromotion['appliedAmount']),
        ];
    }
}
