<?php

namespace App\Repository;

use App\Entity\Coupon;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Coupon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coupon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coupon[]    findAll()
 * @method Coupon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    /**
     * @throws QueryException
     * @throws NonUniqueResultException
     */
    public function findRedeemableCouponCode($value)
    {
        return $this->createQueryBuilder('coupon')
            ->addCriteria($this->redeemableCouponCriteria($value))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByCodeExistInCart($code, $cartId)
    {

        return $this->createQueryBuilder('coupon')
            ->andWhere('coupon.couponCode = :code')
            ->leftJoin('coupon.carts', 'cart_coupon')
            ->andWhere('cart_coupon.id = :cartId')
            ->setParameter('code', $code)
            ->setParameter('cartId', $cartId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function redeemableCouponCriteria($value): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->lte('startDateTime', Carbon::now('Asia/Kolkata')->toDateTimeString()))
            ->andWhere(Criteria::expr()->gt('endDateTime', Carbon::now('Asia/Kolkata')->toDateTimeString()))
            ->andWhere(Criteria::expr()->eq('couponCode', $value))
            ->andWhere(Criteria::expr()->eq('isActive', true))
            ;
    }
}
