<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorFixture extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->authors() as $author) {
            $category = new Author();
            $category->setName($author);
            $category->setSlug($this->slugger->slug($author)->toString());
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
