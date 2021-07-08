<?php

namespace App\Service\Cart\Promotions;

use App\Repository\CategoryRepository;
use App\Service\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;

class CategoryBookPromotion implements PromotionInterface
{
    private $books;
    private Money $total;
    private CategoryRepository $categoryRepository;

    public function __construct(Money $total, $books, CategoryRepository $categoryRepository)
    {
        $this->total = $total;
        $this->books = $books;
        $this->categoryRepository = $categoryRepository;
    }

    public function apply()
    {
        return $this->promotionAppliedAmount();
    }

    private function promotionAppliedAmount(): array
    {
        if (! $this->checkPromotionCanApply()) {
            return [
                'appliedAmount' => new Money(0),
                'isDiscountApplied' => false,
                'discountName' => 'Buy 10 books and get 5 % from store for each category ',
                'remainingPrice' => $this->total,
            ];
        }

        $totalPrice = new Money($this->total->amount());

        $appliedAmount = $this->total->multiply('5')->divide('100');

        $remainingTotalPrice = $totalPrice->subtract($appliedAmount);

        if (-1 == gmp_sign($remainingTotalPrice->amount()) || 0 == gmp_sign($remainingTotalPrice->amount())) {
            return [
                'appliedAmount' => $appliedAmount,
                'isDiscountApplied' => true,
                'discountName' => 'Buy 10 books and get 5 % from store for each category ',
                'remainingPrice' => new Money(0),
            ];
        }

        return [
            'appliedAmount' => $appliedAmount,
            'isDiscountApplied' => true,
            'discountName' => 'Buy 10 books and get 5 % from store for each category ',
            'remainingPrice' => $remainingTotalPrice,
        ];
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     *
     * @todo Refactor
     */
    private function checkPromotionCanApply(): bool
    {
        $categoryCountCollection = new ArrayCollection();

        $this->books->map(function ($bookCart) use ($categoryCountCollection) {
            $categorySlug = $bookCart->getBook()->getCategory()->getSlug();
            $bookCartQuantity = $bookCart->getQuantity();
            if ($categoryCountCollection->containsKey($categorySlug)) {
                $getOldValue = $categoryCountCollection->get($categorySlug);
                $newQuantity = $getOldValue + $bookCartQuantity;
                $categoryCountCollection[$categorySlug] = $newQuantity;
            } else {
                $categoryCountCollection[$categorySlug] = $bookCartQuantity;
            }
        });

        $databaseCategoryCount = $this->categoryRepository->getCategoryCount();

        if ($databaseCategoryCount > $categoryCountCollection->count()) {
            return false;
        }

        $collectionCategory = $categoryCountCollection->filter(function ($category) {
            if ($category >= 10) {
                return $category;
            }
        });

        if ($databaseCategoryCount > $collectionCategory->count()) {
            return false;
        }

        return true;
    }
}
