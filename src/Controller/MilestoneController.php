<?php

namespace App\Controller;

use App\Entity\Milestone;
use App\Repository\MilestoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/milestones')]
final class MilestoneController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MilestoneRepository $milestoneRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}


    #[Route('', name: 'api_milestone_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $milestones = $this->milestoneRepository->findAll();
        $json = $this->serializer->serialize($milestones, 'json', ['groups' => 'milestone:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_milestone_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $milestone = $this->milestoneRepository->find($id);

        if (!$milestone) {
            return new JsonResponse(['error' => 'Milestone not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($milestone, 'json', ['groups' => 'milestone:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('', name: 'api_milestone_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $milestone = $this->serializer->deserialize($request->getContent(), Milestone::class, 'json');
            
            $errors = $this->validator->validate($milestone);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->persist($milestone);
            $this->entityManager->flush();

            $json = $this->serializer->serialize($milestone, 'json', ['groups' => 'milestone:read']);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_milestone_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $milestone = $this->milestoneRepository->find($id);

        if (!$milestone) {
            return new JsonResponse(['error' => 'Milestone not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Milestone::class,
                'json',
                ['object_to_populate' => $milestone]
            );

            $errors = $this->validator->validate($milestone);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }

    
    #[Route('/{id}', name: 'api_milestone_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $milestone = $this->milestoneRepository->find($id);

        if (!$milestone) {
            return new JsonResponse(['error' => 'Milestone not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($milestone);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
