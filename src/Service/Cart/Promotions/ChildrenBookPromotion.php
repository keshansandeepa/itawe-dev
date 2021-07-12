<?php

namespace App\Service\Cart\Promotions;

use App\Service\Money;

class ChildrenBookPromotion implements PromotionInterface
{
    private $books;
    private Money $total;
    private string $discountName = '10% discount When you purchase 5 children books';

    public function __construct(Money $total, $books)
    {
        $this->total = $total;
        $this->books = $books;
    }

    /**
     * @todo Refactor
     */
    public function apply(): PromotionalDetails
    {
        $filterBooks = $this->getChildrenBookCollections();

        return $this->promotionAppliedAmount($filterBooks);
    }

    private function promotionAppliedAmount($filterBooks): PromotionalDetails
    {
        $totalAmount = $this->total->amount();
        

        if (! $this->checkPromotionCanApply($filterBooks) || -1 == gmp_sign($totalAmount) || 0 == gmp_sign($totalAmount)) {
            return new PromotionalDetails(
                new Money(0),
                false,
                $this->discountName,
                $this->total
            );
        }

        return $this->appliedAmount($filterBooks);
    }

    private function appliedAmount($filterBooks): PromotionalDetails
    {
        $books_total_price = new Money(0);
        $filterBooks->map(function ($book) use (&$books_total_price) {
            return $books_total_price->add($book->getTotalPrice());
        });

        $appliedAmount = $books_total_price->multiply('10')->divide('100');

        $remainingTotal = $this->total->subtract($appliedAmount);

        if (0 == gmp_sign($remainingTotal->amount()) || -1 == gmp_sign($remainingTotal->amount())) {
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
            $remainingTotal
        );
    }

    private function checkPromotionCanApply($filterBooks): bool
    {
        $totalQuantity = 0;

        $filterBooks->map(function ($book) use (&$totalQuantity) {
            return $totalQuantity += $book->getQuantity();
        });

        
        if ($totalQuantity >= 5) {

            return true;
        }

        return false;
    }

    private function getChildrenBookCollections()
    {

        return $this->books->filter(function ($bookCart) {
            return 'children' == $bookCart->getBook()->getCategory()->getSlug();
        });
    }
}
