<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private Slugify $slugger;

    public function __construct()
    {
        $this->slugger = new Slugify();
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->dataObject() as $key => $value) {
            $category = new Category();
            $category->setName($key);
            $category->setSlug($this->slugger->slugify($key));
            $category->setPosition($value);
            $manager->persist($category);

            $objects[] = $category;
        }

        $manager->flush();
    }

    private function dataObject()
    {
        return [
            'Children' => 1,
            'Fiction' => 2,
            'Fantasy' => 3,
            'History' => 4,
        ];
    }
}
