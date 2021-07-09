<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="books")
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="cart")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getBookId()
    {
        return $this->getBook()->getId();
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getBookTitle()
    {
        return $this->getBook()->getTitle();
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getBookIsbn()
    {
        return $this->getBook()->getIsbn();
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getTotalPrice()
    {
        return $this->getBookPrice()->multiply($this->getQuantity());
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getBookPriceFormatted()
    {
        return $this->getBookPrice()->formatted();
    }

    public function getBookPrice()
    {
        return $this->getBook()->getPrice();
    }

    /**
     * @Groups({"cart:index"})
     */
    public function getBookCover(): ?string
    {
        return $this->getBook()->getDesktopCoverImage();
    }

    /**
     * @return bool|string
     * @Groups({"cart:index"})
     */
    public function getTotalPriceFormatted()
    {
        return $this->getTotalPrice()->formatted();
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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
