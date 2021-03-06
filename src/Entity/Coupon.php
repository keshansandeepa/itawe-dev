<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 * @Table(name="coupons")
 */
class Coupon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $couponCode;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couponType;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $couponValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $couponPercentOff;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="coupon")
     */
    private $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(string $couponCode): self
    {
        $this->couponCode = $couponCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getCouponType(): ?string
    {
        return $this->couponType;
    }

    public function setCouponType(string $couponType): self
    {
        $this->couponType = $couponType;

        return $this;
    }

    public function getCouponValue(): ?string
    {
        return $this->couponValue;
    }

    public function setCouponValue(?string $couponValue): self
    {
        $this->couponValue = $couponValue;

        return $this;
    }

    public function getCouponPercentOff(): ?int
    {
        return $this->couponPercentOff;
    }

    public function setCouponPercentOff(?int $couponPercentOff): self
    {
        $this->couponPercentOff = $couponPercentOff;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (! $this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setCoupon($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getCoupon() === $this) {
                $cart->setCoupon(null);
            }
        }

        return $this;
    }
}
