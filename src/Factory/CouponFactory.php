<?php

namespace App\Factory;

use App\Entity\Coupon;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static       Coupon|Proxy createOne(array $attributes = [])
 * @method static       Coupon[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static       Coupon|Proxy find($criteria)
 * @method static       Coupon|Proxy findOrCreate(array $attributes)
 * @method static       Coupon|Proxy first(string $sortedField = 'id')
 * @method static       Coupon|Proxy last(string $sortedField = 'id')
 * @method static       Coupon|Proxy random(array $attributes = [])
 * @method static       Coupon|Proxy randomOrCreate(array $attributes = [])
 * @method static       Coupon[]|Proxy[] all()
 * @method static       Coupon[]|Proxy[] findBy(array $attributes)
 * @method static       Coupon[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static       Coupon[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static       CouponRepository|RepositoryProxy repository()
 * @method Coupon|Proxy create($attributes = [])
 */
final class CouponFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $couponType = $this->generateCouponType();
        $starting_date = self::faker()->dateTimeBetween('now', '+5 days', 'Asia/Kolkata');
        $endDate = self::faker()->dateTimeBetween($starting_date, '+5 days', 'Asia/KolKata');

        return [
            'coupon_code' => self::faker()->isbn10(),
            'start_date_time' => $starting_date,
            'end_date_time' => $endDate,
            'coupon_type' => $couponType,
            'coupon_value' => CouponType::fixed == $couponType ? self::faker()->numberBetween(900, 1500) : null,
            'coupon_percent_off' => CouponType::percent == $couponType ? 15 : null,
            'is_active' => self::faker()->randomElement([true, false]),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Coupon $coupon) {})
        ;
    }

    protected static function getClass(): string
    {
        return Coupon::class;
    }

    protected function generateCouponType()
    {
        return self::faker()->randomElement([CouponType::percent]);
    }
}
