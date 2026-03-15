<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private SerializerInterface $serializer
    ) {}

    #[Route('/auth/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return new JsonResponse(['message' => 'El correo ya está registrado'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setLastName($data['lastname']);
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'token' => 'mock_token_' . bin2hex(random_bytes(16)),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'lastname' => $user->getLastName()
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Credenciales inválidas'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'token' => 'mock_token_' . bin2hex(random_bytes(16)),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'lastname' => $user->getLastName()
            ]
        ], Response::HTTP_OK);
    }
}
