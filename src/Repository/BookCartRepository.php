<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCart::class);
    }

    public function findBookCart($book, $cart)
    {
        return $this->createQueryBuilder('findBookCart')
            ->andWhere('findBookCart.book = :book')
            ->andWhere('findBookCart.cart = :cart')
            ->setParameter('book', $book)
            ->setParameter('cart', $cart)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
