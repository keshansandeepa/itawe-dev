<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->dataObject() as $key => $value)
        {
            $category = new Category();
            $category->setName($key);
            $category->setSlug($this->slugger->slug($key)->toString());
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
            'Fiction' =>  2,
            'Fantasy' => 3,
            'History' =>4
        ];
    }
}