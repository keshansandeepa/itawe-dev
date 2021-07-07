<?php

namespace App\Entity;

use App\Repository\CouponRepository;
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
    private $coupon_code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $StartDateTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $EndDateTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CouponType;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $CouponValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CouponPercentOff;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouponCode(): ?string
    {
        return $this->coupon_code;
    }

    public function setCouponCode(string $coupon_code): self
    {
        $this->coupon_code = $coupon_code;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->StartDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $StartDateTime): self
    {
        $this->StartDateTime = $StartDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->EndDateTime;
    }

    public function setEndDateTime(?\DateTimeInterface $EndDateTime): self
    {
        $this->EndDateTime = $EndDateTime;

        return $this;
    }

    public function getCouponType(): ?string
    {
        return $this->CouponType;
    }

    public function setCouponType(string $CouponType): self
    {
        $this->CouponType = $CouponType;

        return $this;
    }

    public function getCouponValue(): ?string
    {
        return $this->CouponValue;
    }

    public function setCouponValue(?string $CouponValue): self
    {
        $this->CouponValue = $CouponValue;

        return $this;
    }

    public function getCouponPercentOff(): ?int
    {
        return $this->CouponPercentOff;
    }

    public function setCouponPercentOff(?int $CouponPercentOff): self
    {
        $this->CouponPercentOff = $CouponPercentOff;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(bool $IsActive): self
    {
        $this->IsActive = $IsActive;

        return $this;
    }
}
