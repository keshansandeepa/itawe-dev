<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 * @Table(name="carts")
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cart:index"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cart", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=BookCart::class, mappedBy="cart")
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|BookCart[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(BookCart $book): self
    {
        if (! $this->books->contains($book)) {
            $this->books[] = $book;
            $book->setCart($this);
        }

        return $this;
    }

    public function removeBook(BookCart $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getCart() === $this) {
                $book->setCart(null);
            }
        }

        return $this;
    }
}
