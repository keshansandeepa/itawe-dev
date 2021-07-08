<?php

namespace App\Service\Coupon;

use App\Exception\InvalidCouponException;
use App\Exception\RedeemedCouponException;
use App\Manager\CartManager;
use App\Repository\CouponRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Symfony\Component\Security\Core\Security;

class CouponService
{
    private CouponRepository $couponRepository;
    private CartService $cartService;
    private Security $security;

    public function __construct(CouponRepository $couponRepository, Security $security, CartService $cartService)
    {
        $this->couponRepository = $couponRepository;
        $this->security = $security;
        $this->cartService = $cartService;
    }

    /**
     * @throws InvalidCouponException
     * @throws RedeemedCouponException
     */
    public function redeem(string $couponCode, CartManager $cartManager): bool
    {
        if ($this->isCartCouponHasAlreadyRedeem()) {
            throw new RedeemedCouponException();
        }
        $validCoupon = $this->getValidCoupon($couponCode);

        if (empty($validCoupon)) {
            throw new InvalidCouponException();
        }

        $cartManager->addCouponCode($this->cartService->getUserCartPayload(), $validCoupon);

        return true;
    }

    /**
     * @throws NonUniqueResultException
     * @throws InvalidCouponException
     */
    public function removeCoupon(string $couponCode, CartManager $cartManager): bool
    {
        $coupon = $this->couponRepository->findByCodeExistInCart($couponCode, $this->security->getUser()->getCart()->getId());
        if (empty($coupon)) {
            throw new InvalidCouponException();
        }
        $cartManager->removeCouponCode($this->cartService->getUserCartPayload(), $coupon);

        return true;
    }

    /**
     * @throws QueryException
     * @throws NonUniqueResultException
     */
    protected function getValidCoupon(string $couponCode)
    {
        return $this->couponRepository->findRedeemableCouponCode($couponCode);
    }

    public function isCartCouponHasAlreadyRedeem(): bool
    {
        if (empty($this->security->getUser()->getCart()->getCoupon())) {
            return false;
        }

        return true;
    }
}
