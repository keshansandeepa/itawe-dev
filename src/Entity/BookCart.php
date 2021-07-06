<?php


namespace App\Entity;
use App\Service\Money;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_cart")
 */
class BookCart
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne (targetEntity= Book::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="books")
     */
    private $cart;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param mixed $book
     */
    public function setBook($book): void
    {
        $this->book = $book;
    }

    /**
     *
     * @Groups({"cart:index"})
     */

    public function getBookId()
    {
        return $this->getBook()->getId();
    }

    /**
     *
     * @Groups({"cart:index"})
     */

    public function getBookTitle()
    {
        return $this->getBook()->getTitle();
    }

    /**
     *
     * @Groups({"cart:index"})
     */

    public function getBookIsbn()
    {
       return $this->getBook()->getIsbn();
    }

    /**
     *
     * @Groups({"cart:index"})
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     *
     * @Groups({"cart:index"})
     */

    public function getTotalPrice()
    {
        return $this->quantity * $this->getBook()->getPrice();
    }

    /**
     * @return bool|string
     * @Groups({"cart:index"})
     */
    public function getTotalPriceFormatted()
    {
        return (new Money($this->getTotalPrice()))->formatted();
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }





}