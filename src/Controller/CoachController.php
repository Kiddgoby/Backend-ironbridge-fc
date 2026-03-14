<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[Route('/api/coaches')]
#[OA\Tag(name: 'Coaches')]
final class CoachController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CoachRepository $coachRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}


    #[Route('', name: 'api_coach_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $coaches = $this->coachRepository->findAll();
        $json = $this->serializer->serialize($coaches, 'json', ['groups' => 'coach:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/group/{group}', name: 'api_coach_by_group', methods: ['GET'])]
    public function indexByGroup(string $group): JsonResponse
    {
        $allowedGroups = ['GK', 'DEF', 'MID', 'FWD', 'HEAD'];

        if (!in_array($group, $allowedGroups)) {
            return new JsonResponse(
                ['error' => 'Invalid group. Allowed values: ' . implode(', ', $allowedGroups)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $coaches = $this->coachRepository->findByGroup($group);
        $json = $this->serializer->serialize($coaches, 'json', ['groups' => 'coach:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_coach_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $coach = $this->coachRepository->find($id);

        if (!$coach) {
            return new JsonResponse(['error' => 'Coach not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($coach, 'json', ['groups' => 'coach:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('', name: 'api_coach_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $coach = $this->serializer->deserialize($request->getContent(), Coach::class, 'json');

            $errors = $this->validator->validate($coach);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->persist($coach);
            $this->entityManager->flush();

            $json = $this->serializer->serialize($coach, 'json', ['groups' => 'coach:read']);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_coach_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $coach = $this->coachRepository->find($id);

        if (!$coach) {
            return new JsonResponse(['error' => 'Coach not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Coach::class,
                'json',
                ['object_to_populate' => $coach]
            );

            $errors = $this->validator->validate($coach);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_coach_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $coach = $this->coachRepository->find($id);

        if (!$coach) {
            return new JsonResponse(['error' => 'Coach not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($coach);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
