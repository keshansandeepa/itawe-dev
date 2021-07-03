<?php


namespace App\Controller;


use App\Entity\Book;
use App\Service\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{


    public function index(EntityManagerInterface $entityManager)
    {

        $repository = $entityManager->getRepository(Book::class);
        $book = $repository->findByIsbn("979-8669081621");

        dd($book);


        return $this->json((new Money(600))->formatted());
    }

}