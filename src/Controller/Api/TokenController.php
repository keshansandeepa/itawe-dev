<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends BaseApiController
{
    public function newTokenAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => $request->getUser()]);
        if (! $user) {
            throw $this->createNotFoundException();
        }
        $isValid = $this->get('security.user_password_hasher')
            ->isPasswordValid($user, $request->getPassword());
        if (! $isValid) {
            throw new BadCredentialsException();
        }
        $token = $this->get('lexik_jwt_authentication.encoder')->encode([
           'username' => $user->getUsername(),
        ]);

        return new JsonResponse([
            'token' => $token,
        ]);
    }
}
