<?php

namespace App\Service\Coupon;

use App\Exception\InvalidCouponException;
use App\Exception\RedeemedCouponException;
use App\Exception\SuccessfullyRedeemedCouponException;
use App\Manager\CartManager;
use App\Repository\CouponRepository;
use App\Service\Cart\CartService;
use Symfony\Component\Security\Core\Security;

class CouponService
{
    private string $couponCode;
    private CouponRepository $couponRepository;

    public function __construct(CouponRepository $couponRepository, Security $security)
    {
        $this->couponRepository = $couponRepository;
        $this->security = $security;
    }

    /**
     * @throws InvalidCouponException
     * @throws RedeemedCouponException
     * @throws SuccessfullyRedeemedCouponException
     */
    public function redeem(string $couponCode, CartManager $cartManager, CartService $cartService)
    {
        if ($this->isCouponHasAlreadyRedeem()) {
            throw new RedeemedCouponException();
        }
        $validCoupon = $this->getValidCoupon($couponCode);

        if (empty($validCoupon)) {
            throw new InvalidCouponException();
        }

        $cartManager->addCouponCode($cartService->getUserCartPayload(), $validCoupon);

        throw new SuccessfullyRedeemedCouponException();
    }

    protected function getValidCoupon(string $couponCode)
    {
        return $this->couponRepository->findRedeemableCouponCode($couponCode);
    }

    protected function isCouponHasAlreadyRedeem(): bool
    {
        if (empty($this->security->getUser()->getCart()->getCoupon())) {
            return false;
        }

        return true;
    }
}
