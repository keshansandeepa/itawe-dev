<?php

namespace App\DataFixtures;

use App\Factory\CouponFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        CouponFactory::createMany(5);
    }
}
