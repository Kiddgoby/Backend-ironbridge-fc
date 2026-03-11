<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/players')]
final class PlayerController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PlayerRepository $playerRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    
    #[Route('', name: 'api_player_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $players = $this->playerRepository->findAll();
        $json = $this->serializer->serialize($players, 'json', ['groups' => 'player:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_player_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            return new JsonResponse(['error' => 'Player not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($player, 'json', ['groups' => 'player:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('', name: 'api_player_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $player = $this->serializer->deserialize($request->getContent(), Player::class, 'json');
            
            $errors = $this->validator->validate($player);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->persist($player);
            $this->entityManager->flush();

            $json = $this->serializer->serialize($player, 'json', ['groups' => 'player:read']);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_player_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            return new JsonResponse(['error' => 'Player not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Player::class,
                'json',
                ['object_to_populate' => $player]
            );

            $errors = $this->validator->validate($player);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_player_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            return new JsonResponse(['error' => 'Player not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($player);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
