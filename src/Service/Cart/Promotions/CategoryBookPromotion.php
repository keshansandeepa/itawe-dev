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
    private string $discountName = 'Buy 10 books and get 5 % from store for each category';


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

    private function promotionAppliedAmount(): PromotionalDetails
    {
        if (! $this->checkPromotionCanApply()) {
            return new PromotionalDetails(
                new Money(0),
                false,
                $this->discountName,
                $this->total
            );
        }

        $totalPrice = new Money($this->total->amount());

        $appliedAmount = $this->total->multiply('5')->divide('100');

        $remainingTotalPrice = $totalPrice->subtract($appliedAmount);

        if (-1 == gmp_sign($remainingTotalPrice->amount()) || 0 == gmp_sign($remainingTotalPrice->amount())) {
            return new PromotionalDetails(
                $appliedAmount,
                true,
                $this->discountName,
                new Money(0)
            );

        }

        return new PromotionalDetails(
            $appliedAmount,
            true,
            $this->discountName,
            $remainingTotalPrice
        );

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
