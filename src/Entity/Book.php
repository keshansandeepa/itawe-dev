<?php

namespace App\Entity;

use App\Repository\BookRepository;
use App\Service\Money;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @Table(name="books")
 */
class Book
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_book", "list_book"})
     */
    private $id;

    /**
     * @Groups({"show_book", "list_book"})
     * @ORM\Column(type="string", length="255", unique=true)
     */
    private $isbn;

    /**
     * @Groups({"show_book", "list_book"})
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @Groups({"show_book", "list_book"})
     * @ORM\Column (type="string", length="255", unique=true,nullable=true)
     */
    private $slug;

    /**
     * @Groups({"show_book", "list_book"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", name="publication_date")
     */
    private $publicationDate;

    /**
     * @Groups({"show_book", "list_book"})
     * @SerializedName("publicationDate ")
     */
    private $publicationDateFormatted;

    /**
     * @ORM\Column(type="text", nullable=true, name="desktop_cover_image")
     * @Groups({"show_book", "list_book"})
     */
    private $desktopCoverImage;

    /**
     * @ORM\Column(type="text", nullable=true, name="mobile_cover_image")
     * @Groups({"show_book", "list_book"})
     */
    private $mobileCoverImage;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Groups({"show_book", "list_book"})
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     * @Groups({"show_book"})
     */
    private $authors;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=BookCart::class, mappedBy="book", orphanRemoval=true)
     */
    private $cart;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->cart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function getPublicationDateFormatted()
    {
        return $this->publicationDateFormatted = Carbon::instance($this->getPublicationDate())->toDateString();
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getDesktopCoverImage(): ?string
    {
        return $this->desktopCoverImage;
    }

    public function setDesktopCoverImage(?string $desktopCoverImage): self
    {
        $this->desktopCoverImage = $desktopCoverImage;

        return $this;
    }

    public function getMobileCoverImage(): ?string
    {
        return $this->mobileCoverImage;
    }

    public function setMobileCoverImage(?string $mobileCoverImage): self
    {
        $this->mobileCoverImage = $mobileCoverImage;

        return $this;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price * 100;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    /***
     *
     * @Groups({"show_book"})
     */
    public function getFormattedBookPrice()
    {

        return (new Money($this->getPrice()))->formatted();
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (! $this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|BookCart[]
     */
    public function getCart(): Collection
    {
        return $this->cart;
    }

    public function addCart(BookCart $cart): self
    {
        if (! $this->cart->contains($cart)) {
            $this->cart[] = $cart;
            $cart->setBook($this);
        }

        return $this;
    }

    public function removeCart(BookCart $cart): self
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getBook() === $this) {
                $cart->setBook(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
