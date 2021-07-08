<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    private Slugify $slugger;

    public function __construct()
    {
        $this->slugger = new Slugify();
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->authors() as $author) {
            $category = new Author();
            $category->setName($author);
            $category->setSlug($this->slugger->slugify($author));
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function authors()
    {
        return [
            'Lisa Fipps',
            'Barack Obama',
            'Travis Thrasher',
            'Crystal Radke',
            'Cleo Wade',
            'B. B. Alston',
            'Heather Avis',
            'JJ Heller',
            'Nicola Edwards',
            'Asia Citro',
            'Jenny Laird',
            'Malcolm Gladwell',
        ];
    }
}
