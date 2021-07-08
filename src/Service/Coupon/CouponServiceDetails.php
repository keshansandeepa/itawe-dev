<?php

namespace App\Service\Coupon;

use App\Enum\CouponType;
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
     * @param $total
     *
     * @return string[]
     *
     * @todo logic for FIXED type
     */
    public function appliedCouponDetails(Money $total)
    {
        $userCoupon = $this->security->getUser()->getCart()->getCoupon();
        if (CouponType::percent == $userCoupon->getCouponType()) {
            $couponCodePercentOff = $userCoupon->getCouponPercentOff();
            $appliedAmount = $total->multiply($couponCodePercentOff)->divide(100);
            $remainingTotal = $total->subtract($appliedAmount);

            if (0 == gmp_sign($remainingTotal->amount()) || -1 == gmp_sign($remainingTotal->amount())) {
                return [
                    'appliedAmount' => $appliedAmount,
                    'couponCode' => $userCoupon->getCouponCode(),
                    'remainingTotal' => new Money(0),
                ];
            }

            return [
                'appliedAmount' => $appliedAmount,
                'couponCode' => $userCoupon->getCouponCode(),
                'remainingTotal' => $remainingTotal,
            ];
        }
    }
}
