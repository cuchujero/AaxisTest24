<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TokenAuthenticator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function authenticateToken($request): ?User
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader) {
            return null;
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['token' => $token]);

        return $user;
    }
}
