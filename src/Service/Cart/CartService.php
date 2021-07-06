<?php


namespace App\Service\Cart;
use App\Service\Money;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CartService implements CartInterface
{

    private $security;
    private SerializerInterface $serializer;

    public function __construct(Security $security,SerializerInterface $serializer)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function total() :Money
    {
        $price = 0;
      foreach ($this->products() as $product)
      {
           $price += $product->getTotalPrice();
      }

      return new Money($price);
    }

    public function products()
    {

        return $this->security->getUser()->getCart()->getBooks();
    }
}