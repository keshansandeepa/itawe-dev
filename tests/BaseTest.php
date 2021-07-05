<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseTest  extends ApiTestCase
{
    protected function createUser(string $name,string $email, string $password,UserPasswordHasherInterface $passwordHasher):User
    {
       $user = new User();
       $user->setName($name);
       $user->setEmail($email);
       $user->setPassword($passwordHasher->hashPassword($user, $password));
    }
}