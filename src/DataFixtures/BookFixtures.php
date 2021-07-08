<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Zenstruck\Foundry\faker;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    private AuthorRepository $authorRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(SluggerInterface $slugger, AuthorRepository $authorRepository, CategoryRepository $categoryRepository)
    {
        $this->slugger = $slugger;

        $this->authorRepository = $authorRepository;

        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->books() as $book) {
            $newBook = new Book();
            $newBook->setSlug($book['slug']);
            $newBook->setTitle($book['title']);
            $newBook->setIsbn(faker()->isbn13());
            $newBook->setDescription($book['description']);
            $newBook->setPublicationDate($book['publication_date']);
            $newBook->setPrice($book['price']);
            $newBook->setDesktopCoverImage($book['desktop_cover_image']);
            $newBook->setMobileCoverImage($book['mobile_cover_image']);

            $manager->persist($newBook);
            $author = $this->authorRepository->findBySlug($this->slugger->slug($book['author'])->toString());
            $newBook->addAuthor($author);
            $category = $this->categoryRepository->findBySlug($this->slugger->slug($book['category'])->toString());
            $newBook->setCategory($category);
        }
        $manager->flush();
    }

    private function books()
    {
        return [
            [
                'title' => 'Starfish',
                'slug' => $this->slugger->slug('Starfish')->toString(),
                'description' => <<<EOF
                    Ever since Ellie wore a whale swimsuit and made a big splash at her fifth birthday party, she's been bullied about her weight. To cope, she tries to live by the Fat Girl Rules--like "no making waves," "avoid eating in public," and "don't move so fast that your body jiggles." And she's found her safe space--her swimming pool--where she feels weightless in a fat-obsessed world. In the water, she can stretch herself out like a starfish and take up all the room she wants. It's also where she can get away from her pushy mom, who thinks criticizing Ellie's weight will motivate her to diet. Fortunately, Ellie has allies in her dad, her therapist, and her new neighbor, Catalina, who loves Ellie for who she is. With this support buoying her, Ellie might finally be able to cast aside the Fat Girl Rules and starfish in real life--by unapologetically being her own fabulous self.
                EOF,
                'publication_date' => new \DateTime('2020-09-10'),
                'price' => '3159.96',
                'category' => 'children',
                'author' => 'Lisa Fipps',
                'desktop_cover_image' => '/images/starFish.jpg',
                'mobile_cover_image' => '/images/starFish.jpg',
            ],
            [
                'title' => 'Dude Perfect 101 Tricks, Tips, and Cool Stuff',
                'slug' => $this->slugger->slug('Dude Perfect 101 Tricks, Tips, and Cool Stuff')->toString(),
                'description' => <<<EOF
                    You may know Dude Perfect from their mind-blowing, world record-breaking, viral trick shot videos and hilarious Overtime videos! NOW, with the guys’ new, massive, photo-intensive book Dude Perfect 101 Tricks, Tips, and Cool Stuff, you’ll experience a behind-the-scenes look at their stunts and their personal lives, PLUS step-by-step instructions so you can attempt their tricks at home!
                EOF,
                'publication_date' => new \DateTime('2020-09-10'),
                'price' => '4000.26',
                'category' => 'children',
                'author' => 'Travis Thrasher',
                'desktop_cover_image' => '/images/dudeperfect.jpg',
                'mobile_cover_image' => '/images/dudeperfect.jpg',
            ],

            [
                'title' => 'My First Learn to Write Workbook',
                'slug' => $this->slugger->slug('My First Learn to Write Workbook')->toString(),
                'description' => <<<EOF
                    Set kids up to succeed in school with a learn to write for kids guide that teaches them letters, shapes, and numbers―and makes it fun. My First Learn-to-Write Workbook introduces early writers to proper pen control, line tracing, and more with dozens of handwriting exercises that engage their minds and boost their reading and writing comprehension.
                EOF,
                'publication_date' => new \DateTime('2018-09-10'),
                'price' => '3159',
                'category' => 'children',
                'author' => 'Crystal Radke',
                'desktop_cover_image' => '/images/abc.jpg',
                'mobile_cover_image' => '/images/abc.jpg',
            ],
            [
                'title' => 'What the Road Said',
                'slug' => $this->slugger->slug('What the Road Said')->toString(),
                'description' => <<<EOF
                   What the Road Said is the New York Times-bestselling comforting and uplifting picture book from bestselling poet and activist Cleo Wade.
                EOF,
                'publication_date' => new \DateTime('2019-08-10'),
                'price' => '2779.96',
                'category' => 'children',
                'author' => 'Cleo Wade',
                'desktop_cover_image' => '/images/celowade.jpg',
                'mobile_cover_image' => '/images/celowade.jpg',
            ],

            [
                'title' => 'Amari and the Night Brothers',
                'slug' => $this->slugger->slug('Amari and the Night Brothers')->toString(),
                'description' => <<<EOF
                 Artemis Fowl meets Men in Black in this exhilarating debut middle grade fantasy, the first in a trilogy filled with #blackgirlmagic. Perfect for fans of Tristan Strong Punches a Hole in the Sky, the Percy Jackson series, and Nevermoor.
                EOF,
                'publication_date' => new \DateTime('2016-08-10'),
                'price' => '3384.28',
                'category' => 'children',
                'author' => 'B. B. Alston',
                'desktop_cover_image' => '/images/amari.jpg',
                'mobile_cover_image' => '/images/amari.jpg',
            ],
            [
                'title' => 'Different--A Great Thing to Be!',
                'slug' => $this->slugger->slug('Different--A Great Thing to Be!')->toString(),
                'description' => <<<EOF
                 Different--A Great Thing to Be!
                EOF,
                'publication_date' => new \DateTime('2020-08-10'),
                'price' => '1384.28',
                'category' => 'fiction',
                'author' => 'Heather Avis',
                'desktop_cover_image' => '/images/different.jpg',
                'mobile_cover_image' => '/images/different.jpg',
            ],

            [
                'title' => 'Hand to Hold',
                'slug' => $this->slugger->slug('Hand to Hold')->toString(),
                'description' => <<<EOF
                 This heartwarming picture book reassures children that a parent’s love never lets go—based on the poignant lyrics of JJ Heller’s beloved lullaby “Hand to Hold.”
                EOF,
                'publication_date' => new \DateTime('2020-06-10'),
                'price' => '1684.28',
                'category' => 'fiction',
                'author' => 'JJ Heller',
                'desktop_cover_image' => '/images/handsonhold.jpg',
                'mobile_cover_image' => '/images/handsonhold.jpg',
            ],

            [
                'title' => "You're My Little Bookworm",
                'slug' => $this->slugger->slug("You're My Little Bookworm")->toString(),
                'description' => <<<EOF
                 This sweet, rhyming story with interactive die-cuts is perfect to share with your own little bookworm!
                EOF,
                'publication_date' => new \DateTime('2020-06-10'),
                'price' => '1684.28',
                'category' => 'fiction',
                'author' => 'Nicola Edwards',
                'desktop_cover_image' => '/images/bookworm.jpg',
                'mobile_cover_image' => '/images/bookworm.jpg',
            ],

            [
                'title' => 'Zoey and Sassafras Boxed Set',
                'slug' => $this->slugger->slug('Zoey and Sassafras Boxed Set')->toString(),
                'description' => <<<EOF
                 Follow the adventures of Zoey and her cat, Sassafras, with this collection of books one to six in the series.
                EOF,
                'publication_date' => new \DateTime('2020-05-10'),
                'price' => '1884.28',
                'category' => 'fiction',
                'author' => 'Asia Citro',
                'desktop_cover_image' => '/images/zoey.jpg',
                'mobile_cover_image' => '/images/zoey.jpg',
            ],

            [
                'title' => 'Dinosaurs Before Dark Graphic Novel',
                'slug' => $this->slugger->slug('Dinosaurs Before Dark Graphic Novel')->toString(),
                'description' => <<<EOF
                 The #1 bestselling chapter book is now a graphic novel! Magic. Mystery. Time-travel. Get whisked back in time in the magic tree house with Jack and Annie!.
                EOF,
                'publication_date' => new \DateTime('2020-05-10'),
                'price' => '1884.28',
                'category' => 'fiction',
                'author' => 'Jenny Laird',
                'desktop_cover_image' => '/images/magictreehouse.jpg',
                'mobile_cover_image' => '/images/magictreehouse.jpg',
            ],

            [
                'title' => 'The Bomber Mafia: A Dream, a Temptation, and the Longest Night of the Second World War',
                'slug' => $this->slugger->slug('The Bomber Mafia: A Dream, a Temptation, and the Longest Night of the Second World War')->toString(),
                'description' => <<<EOF
                 In The Bomber Mafia: A Dream, a Temptation, and the Longest Night of the Second World War, Malcolm Gladwell, author of New York Times best sellers including Talking to Strangers and host of the podcast Revisionist History, uses original interviews, archival footage, and his trademark insight to weave together the stories of a Dutch genius and his homemade computer, a band of brothers in central Alabama, a British psychopath, and pyromaniacal chemists at Harvard. As listeners hear these stories unfurl, Gladwell examines one of the greatest moral challenges in modern American history.
                EOF,
                'publication_date' => new \DateTime('2021-04-27'),
                'price' => '1884.28',
                'category' => 'History',
                'author' => 'Malcolm Gladwell',
                'desktop_cover_image' => '/images/bombermafia.jpeg',
                'mobile_cover_image' => '/images/bombermafia.jpeg',
            ],

            [
                'title' => 'A Promised Land',
                'slug' => $this->slugger->slug('A Promised Land')->toString(),
                'description' => <<<EOF
                 A riveting, deeply personal account of history in the making - from the president who inspired us to believe in the power of democracy NUMBER ONE NEW YORK TIMES BESTSELLER NAACP IMAGE AWARD NOMINEE NAMED ONE OF THE TEN BEST BOOKS OF THE YEAR BY THE NEW YORK TIMES BOOK REVIEW
                EOF,
                'publication_date' => new \DateTime('2020-12-08'),
                'price' => '1884.28',
                'category' => 'History',
                'author' => 'Barack Obama',
                'desktop_cover_image' => '/images/promiseland.jpeg',
                'mobile_cover_image' => '/images/promiseland.jpeg',
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
