<?php

namespace App\Service\Coupon;

use App\Enum\CouponType;
use App\Exception\InvalidCouponException;
use App\Exception\RedeemedCouponException;
use App\Manager\CartManager;
use App\Repository\CouponRepository;
use App\Service\Money;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Symfony\Component\Security\Core\Security;

class CouponService
{
    private CouponRepository $couponRepository;
    private Security $security;
    private CartManager $cartManager;

    public function __construct(CouponRepository $couponRepository, Security $security, CartManager $cartManager)
    {
        $this->couponRepository = $couponRepository;
        $this->security = $security;
        $this->cartManager = $cartManager;
    }

    /**
     * @throws InvalidCouponException
     * @throws RedeemedCouponException
     */
    public function redeem(string $couponCode): bool
    {
        if ($this->isCartCouponHasAlreadyRedeem()) {
            throw new RedeemedCouponException();
        }
        $validCoupon = $this->getValidCoupon($couponCode);

        if (empty($validCoupon)) {
            throw new InvalidCouponException();
        }

        $this->cartManager->addCouponCode($this->security->getUser()->getCart(), $validCoupon);

        return true;
    }

    /**
     * @throws NonUniqueResultException
     * @throws InvalidCouponException
     */
    public function removeCoupon(string $couponCode): bool
    {
        $coupon = $this->couponRepository->findByCodeExistInCart($couponCode, $this->security->getUser()->getCart()->getId());
        if (empty($coupon)) {
            throw new InvalidCouponException();
        }
        $this->cartManager->removeCouponCode($this->security->getUser()->getCart(), $coupon);

        return true;
    }

    public function isCartCouponHasAlreadyRedeem(): bool
    {
        if (empty($this->security->getUser()->getCart()->getCoupon())) {
            return false;
        }

        return true;
    }

    public function appliedCouponDetails(Money $total): CartCouponDetails
    {
        $userCoupon = $this->security->getUser()->getCart()->getCoupon();

        if (CouponType::percent == $userCoupon->getCouponType()) {
            $couponCodePercentOff = $userCoupon->getCouponPercentOff();
            $oldTotal = new Money($total->amount());
            $appliedAmount = $total->multiply($couponCodePercentOff)->divide(100);
            $remainingTotal = $oldTotal->subtract($appliedAmount);

            if (0 == gmp_sign($remainingTotal->amount()) || -1 == gmp_sign($remainingTotal->amount())) {
                return new CartCouponDetails($appliedAmount, $userCoupon->getCouponCode(), new Money(0));
            }

            return new CartCouponDetails($appliedAmount, $userCoupon->getCouponCode(), $remainingTotal);
        }
    }

    /**
     * @throws QueryException
     * @throws NonUniqueResultException
     */
    protected function getValidCoupon(string $couponCode)
    {
        return $this->couponRepository->findRedeemableCouponCode($couponCode);
    }
}
