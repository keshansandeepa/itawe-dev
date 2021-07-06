<?php


namespace App\Controller;


use App\DataFixtures\BookFixtures;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{


    public function index(AuthorRepository $authorRepository)
    {
        var_dump($this->getUser()->getCart());;

    }

}