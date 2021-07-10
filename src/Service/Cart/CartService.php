<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Repository\BookRepository;
use App\Service\Coupon\CartCouponDetails;
use App\Service\Coupon\CouponService;
use App\Service\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;

class CartService implements CartInterface
{
    private Security $security;
    private CartDiscountService $cartDiscountService;
    public bool $isUserCartEmpty;
    private CouponService $couponService;

    public function __construct(
        Security $security,
        CouponService $couponService,
        CartDiscountService $cartDiscountService
    ) {
        $this->security = $security;
        $this->couponService = $couponService;
        $this->cartDiscountService = $cartDiscountService;
    }

    /**
     * @return Money
     *               Total
     */
    public function getTotal(): Money
    {
        return $this->getSubTotal();
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

    public function getBooks()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return $this->getUserCartPayload()->getBooks();
    }

    public function getSubTotal(): Money
    {
        return $this->calculateSubtotal($this->getBooksTotal());
    }

    public function getBooksTotal(): Money
    {
        $price = new Money(0);
        if ($this->isUserCartEmpty) {
            return $price;
        }
        $this->getBooks()->map(function ($item) use (&$price) {
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

    public function getUserCartPayload(): Cart
    {
        return $this->security->getUser()->getCart();
    }

    public function getCartDiscountTotal(): Money
    {
        if ($this->isUserCartEmpty) {
            return new Money(0);
        }

        return $this->getCartDiscount(($this->getBooksTotal()))['appliedDiscountTotal'];
    }

    public function getCouponDetails(): CartCouponDetails
    {
        if ($this->isCouponApplied()) {
            return $this->calculateCartCouponDetails($this->getBooksTotal());
        }

        return new CartCouponDetails(new Money(0), '', new Money(0));
    }

    protected function calculateSubtotal(Money $total): Money
    {
        if ($this->isUserCartEmpty) {
            return new Money(0);
        }

        if ($this->isCouponApplied()) {
            return $this->calculateCartCouponDetails($total)->getRemainingTotal();
        }

        return $this->getCartDiscount($total)['remainingTotal'];
    }

    protected function calculateCartCouponDetails($total): CartCouponDetails
    {
        return $this->couponService->appliedCouponDetails($total);
    }

    protected function getCartDiscount($total): array
    {
        return $this->cartDiscountService->apply($total, $this->getBooks());
    }

    protected function isCouponApplied(): bool
    {
        if (empty($this->security->getUser()->getCart())) {
            return false;
        }
        if (empty($this->security->getUser()->getCart()->getCoupon())) {
            return false;
        }

        return true;
    }
}
