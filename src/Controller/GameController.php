<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/games')]
final class GameController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private GameRepository $gameRepository,
        private SerializerInterface $serializer
    ) {}


    #[Route('', name: 'api_game_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $games = $this->gameRepository->findAll();
        $json = $this->serializer->serialize($games, 'json', ['groups' => 'game:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_game_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $game = $this->gameRepository->find($id);

        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($game, 'json', ['groups' => 'game:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('', name: 'api_game_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $game = $this->serializer->deserialize($request->getContent(), Game::class, 'json');
            
            $this->entityManager->persist($game);
            $this->entityManager->flush();

            $json = $this->serializer->serialize($game, 'json', ['groups' => 'game:read']);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_game_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $game = $this->gameRepository->find($id);

        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Game::class,
                'json',
                ['object_to_populate' => $game]
            );

            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    }

    
    #[Route('/{id}', name: 'api_game_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $game = $this->gameRepository->find($id);

        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($game);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
