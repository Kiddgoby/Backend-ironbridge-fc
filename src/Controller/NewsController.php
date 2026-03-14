<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[Route('/api/news')]
#[OA\Tag(name: 'News')]
final class NewsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NewsRepository $newsRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}


    #[Route('', name: 'api_news_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $news = $this->newsRepository->findBy([], ['published_at' => 'DESC']);
        $json = $this->serializer->serialize($news, 'json', ['groups' => 'news:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/featured', name: 'api_news_featured', methods: ['GET'])]
    public function featured(): JsonResponse
    {
        $news = $this->newsRepository->findFeatured();
        $json = $this->serializer->serialize($news, 'json', ['groups' => 'news:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/category/{category}', name: 'api_news_by_category', methods: ['GET'])]
    public function indexByCategory(string $category): JsonResponse
    {
        $allowedCategories = ['MATCH', 'SIGNING', 'CLUB', 'EVENT'];

        if (!in_array($category, $allowedCategories)) {
            return new JsonResponse(
                ['error' => 'Invalid category. Allowed values: ' . implode(', ', $allowedCategories)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $news = $this->newsRepository->findByCategory($category);
        $json = $this->serializer->serialize($news, 'json', ['groups' => 'news:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_news_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return new JsonResponse(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($news, 'json', ['groups' => 'news:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('', name: 'api_news_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $news = $this->serializer->deserialize($request->getContent(), News::class, 'json');

            $errors = $this->validator->validate($news);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->persist($news);
            $this->entityManager->flush();

            $json = $this->serializer->serialize($news, 'json', ['groups' => 'news:read']);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_news_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return new JsonResponse(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize(
                $request->getContent(),
                News::class,
                'json',
                ['object_to_populate' => $news]
            );

            $errors = $this->validator->validate($news);
            if (count($errors) > 0) {
                return new JsonResponse($this->serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'api_news_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return new JsonResponse(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($news);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
