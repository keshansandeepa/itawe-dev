<?php


namespace App\Controller\Auth;


use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    public function login(IriConverterInterface $iriConverter)
    {
        if (! $this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return  $this->json([
                'error' => 'Invalid Login Request'
            ],400);
        }

        return  new Response(null,204,[
            'Location' => $iriConverter->getIriFromItem($this->getUser())
        ]);
        return $this->json([
            'user' => $this->getUser() ? $this->getUser()->getID() : null
        ]);
    }
}