<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
/**
 *
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
     * @Groups ({"book_resource:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length="255", unique=true)
     *
     */
    private $isbn;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $title;

    /**
     * @ORM\Column (type="string", length="255", unique=true,nullable=true)
     *
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", name="publication_date")
     *
     */
    private $publicationDate;

    private $publicationDateFormatted;

    /**
     * @ORM\Column(type="text", nullable=true, name="desktop_cover_image")
     *
     */
    private $desktopCoverImage;

    /**
     * @ORM\Column(type="text", nullable=true, name="mobile_cover_image")
     *
     */
    private $mobileCoverImage;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     *
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     *
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     *
     */
    private $authors;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->authors = new ArrayCollection();
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

    public function setPrice(?int $price):self
    {
        $this->price = $price*100;

        return  $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }


    public function setSlug(?string $slug):self
    {
        $this->slug = $slug;
        return  $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

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
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }
}
