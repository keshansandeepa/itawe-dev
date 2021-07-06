<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

/**

 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Table(name="categories")
 *
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_cateogory", "show_book","list_book"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_category","show_book","list_book"})
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_category","show_book","list_book"})
     *
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *@Groups({"list_category","show_book","list_book"})
     */
    private $position;

    /**
     * @ORM\OneToOne(targetEntity=Book::class, mappedBy="category", cascade={"persist", "remove"})
     */
    private $book;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }



    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeCategory($this);
        }

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        // set the owning side of the relation if necessary
        if ($book->getCategory() !== $this) {
            $book->setCategory($this);
        }

        $this->book = $book;

        return $this;
    }
}
