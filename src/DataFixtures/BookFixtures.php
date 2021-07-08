<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\faker;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    private Slugify $slugger;
    private AuthorRepository $authorRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(AuthorRepository $authorRepository, CategoryRepository $categoryRepository)
    {
        $this->slugger = new Slugify();

        $this->authorRepository = $authorRepository;

        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->books() as $book) {
            $newBook = new Book();
            $newBook->setSlug($this->slugger->slugify($book['slug']));
            $newBook->setTitle($book['title']);
            $newBook->setIsbn(faker()->isbn13());
            $newBook->setDescription($book['description']);
            $newBook->setPublicationDate($book['publication_date']);
            $newBook->setPrice($book['price']);
            $newBook->setDesktopCoverImage($book['desktop_cover_image']);
            $newBook->setMobileCoverImage($book['mobile_cover_image']);
            $newBook->setQuantity($book['quantity']);
            $manager->persist($newBook);
            $author = $this->authorRepository->findBySlug($this->slugger->slugify($book['author']));
            $newBook->addAuthor($author);
            $category = $this->categoryRepository->findBySlug($this->slugger->slugify($book['category']));
            $newBook->setCategory($category);
        }
        $manager->flush();
    }

    private function books()
    {
        return [
            [
                'title' => 'Starfish',
                'slug' => 'Starfish',
                'description' => <<<EOF
                    Ever since Ellie wore a whale swimsuit and made a big splash at her fifth birthday party, she's been bullied about her weight. To cope, she tries to live by the Fat Girl Rules--like "no making waves," "avoid eating in public," and "don't move so fast that your body jiggles." And she's found her safe space--her swimming pool--where she feels weightless in a fat-obsessed world. In the water, she can stretch herself out like a starfish and take up all the room she wants. It's also where she can get away from her pushy mom, who thinks criticizing Ellie's weight will motivate her to diet. Fortunately, Ellie has allies in her dad, her therapist, and her new neighbor, Catalina, who loves Ellie for who she is. With this support buoying her, Ellie might finally be able to cast aside the Fat Girl Rules and starfish in real life--by unapologetically being her own fabulous self.
                EOF,
                'publication_date' => new \DateTime('2020-09-10'),
                'price' => '3159.96',
                'category' => 'children',
                'author' => 'Lisa Fipps',
                'desktop_cover_image' => '/images/starFish.jpg',
                'mobile_cover_image' => '/images/starFish.jpg',
                'quantity' => 10,
            ],
            [
                'title' => 'Dude Perfect 101 Tricks, Tips, and Cool Stuff',
                'slug' => 'Dude Perfect 101 Tricks, Tips, and Cool Stuff',
                'description' => <<<EOF
                    You may know Dude Perfect from their mind-blowing, world record-breaking, viral trick shot videos and hilarious Overtime videos! NOW, with the guys’ new, massive, photo-intensive book Dude Perfect 101 Tricks, Tips, and Cool Stuff, you’ll experience a behind-the-scenes look at their stunts and their personal lives, PLUS step-by-step instructions so you can attempt their tricks at home!
                EOF,
                'publication_date' => new \DateTime('2020-09-10'),
                'price' => '4000.26',
                'category' => 'children',
                'author' => 'Travis Thrasher',
                'desktop_cover_image' => '/images/dudeperfect.jpg',
                'mobile_cover_image' => '/images/dudeperfect.jpg',
                'quantity' => 10,
            ],

            [
                'title' => 'My First Learn to Write Workbook',
                'slug' => 'My First Learn to Write Workbook',
                'description' => <<<EOF
                    Set kids up to succeed in school with a learn to write for kids guide that teaches them letters, shapes, and numbers―and makes it fun. My First Learn-to-Write Workbook introduces early writers to proper pen control, line tracing, and more with dozens of handwriting exercises that engage their minds and boost their reading and writing comprehension.
                EOF,
                'publication_date' => new \DateTime('2018-09-10'),
                'price' => '3159',
                'category' => 'children',
                'author' => 'Crystal Radke',
                'desktop_cover_image' => '/images/abc.jpg',
                'mobile_cover_image' => '/images/abc.jpg',
                'quantity' => 10,
            ],
            [
                'title' => 'What the Road Said',
                'slug' => 'What the Road Said',
                'description' => <<<EOF
                   What the Road Said is the New York Times-bestselling comforting and uplifting picture book from bestselling poet and activist Cleo Wade.
                EOF,
                'publication_date' => new \DateTime('2019-08-10'),
                'price' => '2779.96',
                'category' => 'children',
                'author' => 'Cleo Wade',
                'desktop_cover_image' => '/images/cleowade.jpg',
                'mobile_cover_image' => '/images/cleowade.jpg',
                'quantity' => 30,
            ],

            [
                'title' => 'Amari and the Night Brothers',
                'slug' => 'Amari and the Night Brothers',
                'description' => <<<EOF
                 Artemis Fowl meets Men in Black in this exhilarating debut middle grade fantasy, the first in a trilogy filled with #blackgirlmagic. Perfect for fans of Tristan Strong Punches a Hole in the Sky, the Percy Jackson series, and Nevermoor.
                EOF,
                'publication_date' => new \DateTime('2016-08-10'),
                'price' => '3384.28',
                'category' => 'children',
                'author' => 'B. B. Alston',
                'desktop_cover_image' => '/images/amari.jpeg',
                'mobile_cover_image' => '/images/amari.jpeg',
                'quantity' => 20,
            ],
            [
                'title' => 'Different--A Great Thing to Be!',
                'slug' => 'Different--A Great Thing to Be!',
                'description' => <<<EOF
                 Different--A Great Thing to Be!
                EOF,
                'publication_date' => new \DateTime('2020-08-10'),
                'price' => '1384.28',
                'category' => 'fiction',
                'author' => 'Heather Avis',
                'desktop_cover_image' => '/images/different.jpg',
                'mobile_cover_image' => '/images/different.jpg',
                'quantity' => 5,
            ],

            [
                'title' => 'Hand to Hold',
                'slug' => 'Hand to Hold',
                'description' => <<<EOF
                 This heartwarming picture book reassures children that a parent’s love never lets go—based on the poignant lyrics of JJ Heller’s beloved lullaby “Hand to Hold.”
                EOF,
                'publication_date' => new \DateTime('2020-06-10'),
                'price' => '1684.28',
                'category' => 'fiction',
                'author' => 'JJ Heller',
                'desktop_cover_image' => '/images/handonhold.jpg',
                'mobile_cover_image' => '/images/handonhold.jpg',
                'quantity' => 10,
            ],

            [
                'title' => "You're My Little Bookworm",
                'slug' => "You're My Little Bookworm",
                'description' => <<<EOF
                 This sweet, rhyming story with interactive die-cuts is perfect to share with your own little bookworm!
                EOF,
                'publication_date' => new \DateTime('2020-06-10'),
                'price' => '1684.28',
                'category' => 'fiction',
                'author' => 'Nicola Edwards',
                'desktop_cover_image' => '/images/bookworm.jpg',
                'mobile_cover_image' => '/images/bookworm.jpg',
                'quantity' => 10,
            ],

            [
                'title' => 'Zoey and Sassafras Boxed Set',
                'slug' => 'Zoey and Sassafras Boxed Set',
                'description' => <<<EOF
                 Follow the adventures of Zoey and her cat, Sassafras, with this collection of books one to six in the series.
                EOF,
                'publication_date' => new \DateTime('2020-05-10'),
                'price' => '1884.28',
                'category' => 'fiction',
                'author' => 'Asia Citro',
                'desktop_cover_image' => '/images/zoey.jpg',
                'mobile_cover_image' => '/images/zoey.jpg',
                'quantity' => 10,
            ],

            [
                'title' => 'Dinosaurs Before Dark Graphic Novel',
                'slug' => 'Dinosaurs Before Dark Graphic Novel',
                'description' => <<<EOF
                 The #1 bestselling chapter book is now a graphic novel! Magic. Mystery. Time-travel. Get whisked back in time in the magic tree house with Jack and Annie!.
                EOF,
                'publication_date' => new \DateTime('2020-05-10'),
                'price' => '1884.28',
                'category' => 'fiction',
                'author' => 'Jenny Laird',
                'desktop_cover_image' => '/images/magictreehouse.jpg',
                'mobile_cover_image' => '/images/magictreehouse.jpg',
                'quantity' => 10,
            ],

        ];
    }

    public function getDependencies()
    {
        return [
            AuthorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
