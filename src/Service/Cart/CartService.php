<?php

namespace App\Service\Cart;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\Coupon\CouponServiceDetails;
use App\Service\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;

class CartService implements CartInterface
{
    private Security $security;
    private CategoryRepository $categoryRepository;
    private CouponServiceDetails $couponServiceDetails;
    public bool $isUserCartEmpty;

    public function __construct(
        Security $security,
        CategoryRepository $categoryRepository,
        CouponServiceDetails $couponServiceDetails
    ) {
        $this->security = $security;
        $this->categoryRepository = $categoryRepository;
        $this->couponServiceDetails = $couponServiceDetails;
    }

    /**
     * @return Money
     *               Total
     */
    public function total(): Money
    {
        return $this->subTotal();
    }

    /**
     * @return bool
     *              Check Cart Empty
     */
    public function isEmpty(): bool
    {
        if (empty($this->getUserCartPayload())) {
            $this->isUserCartEmpty = true;

            return true;
        }
        $this->isUserCartEmpty = false;

        return false;
    }

    /**
     * @return array
     */
    public function books()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return $this->getUserCartPayload()->getBooks();
    }

    public function subTotal()
    {
        return $this->calculateSubtotal($this->booksTotal());
    }

    public function booksTotal(): Money
    {
        $price = new Money(0);
        if ($this->isUserCartEmpty) {
            return $price;
        }
        $this->books()->map(function ($item) use (&$price) {
            $price->add($item->getTotalPrice());
        });

        return $price;
    }

    public function getStorePayload(array $books, BookRepository $bookRepository): ArrayCollection
    {
        $requestBookCollection = new ArrayCollection($books);

        return $requestBookCollection->map(function ($bookCollection) use ($bookRepository) {
            return [
                'quantity' => $bookCollection['quantity'],
                'book' => $bookRepository->find($bookCollection['id']),
            ];
        });
    }

    public function getUserCartPayload()
    {
        return $this->security->getUser()->getCart();
    }

    public function getCartDiscountTotal()
    {
        if ($this->isUserCartEmpty) {
            return new Money(0);
        }

        return $this->getCartDiscount(($this->booksTotal()))['appliedDiscountTotal'];
    }

    public function getCouponDetails()
    {
        return $this->calculateCartCouponDetails($this->booksTotal());
    }

    protected function calculateSubtotal($total)
    {
        if ($this->isUserCartEmpty) {
            return new Money(0);
        }
        if ($this->isCouponApplied()) {
            return $this->calculateCartCouponDetails($total)['remainingTotal'];
        }

        return $this->getCartDiscount($total)['remainingTotal'];
    }

    protected function calculateCartCouponDetails($total)
    {
        if ($this->isCouponApplied()) {
            return $this->couponServiceDetails->appliedCouponDetails($total);
        }

        return [
            'appliedAmount' => new Money(0),
            'couponCode' => null,
            'remainingTotal' => $total,
        ];
    }

    protected function getCartDiscount($total)
    {
        return (new CartDiscountService($this->categoryRepository))->apply($total, $this->books());
    }

    protected function isCouponApplied(): bool
    {
        if ($this->isUserCartEmpty) {
            return false;
        }
        if (empty($this->security->getUser()->getCart()->getCoupon())) {
            return false;
        }

        return true;
    }
}
