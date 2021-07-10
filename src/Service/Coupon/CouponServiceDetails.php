<?php

namespace App\Service\Coupon;

use App\Enum\CouponType;
use App\Service\Coupon\CartCouponDetails;
use App\Service\Money;
use Symfony\Component\Security\Core\Security;

class CouponServiceDetails
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @todo logic for FIXED type
     */
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


}
