<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/genres')]
class GenreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    // GET /api/genres - отримати список усіх жанрів
    #[Route('', name: 'genre_list', methods: ['GET'])]
    public function list(GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findAll();

        $data = array_map(fn (Genre $genre) => [
            'id' => $genre->getId(),
            'name' => $genre->getName(),
            'description' => $genre->getDescription(),
        ], $genres);

        return $this->json($data);
    }

    // GET /api/genres/{id} - отримати один жанр за id
    #[Route('/{id}', name: 'genre_show', methods: ['GET'])]
    public function show(int $id, GenreRepository $genreRepository): JsonResponse
    {
        $genre = $genreRepository->find($id);

        if (!$genre) {
            return $this->json(['error' => 'Genre not found'], 404);
        }

        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName(),
            'description' => $genre->getDescription(),
        ]);
    }

    // POST /api/genres - створити новий жанр
    #[Route('', name: 'genre_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        if (!isset($payload['name']) || trim($payload['name']) === '') {
            return $this->json(['error' => 'Field "name" is required'], 400);
        }

        $genre = new Genre();
        $genre->setName($payload['name']);
        $genre->setDescription($payload['description'] ?? null);

        $this->entityManager->persist($genre);
        $this->entityManager->flush();

        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName(),
            'description' => $genre->getDescription(),
        ], 201);
    }

    // PATCH /api/genres/{id} - оновити жанр
    #[Route('/{id}', name: 'genre_update', methods: ['PATCH'])]
    public function update(int $id, Request $request, GenreRepository $genreRepository): JsonResponse
    {
        $genre = $genreRepository->find($id);

        if (!$genre) {
            return $this->json(['error' => 'Genre not found'], 404);
        }

        $payload = json_decode($request->getContent(), true);

        if (array_key_exists('name', $payload)) {
            $genre->setName($payload['name']);
        }

        if (array_key_exists('description', $payload)) {
            $genre->setDescription($payload['description']);
        }

        $this->entityManager->flush();

        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName(),
            'description' => $genre->getDescription(),
        ]);
    }

    // DELETE /api/genres/{id} - видалити жанр
    #[Route('/{id}', name: 'genre_delete', methods: ['DELETE'])]
    public function delete(int $id, GenreRepository $genreRepository): JsonResponse
    {
        $genre = $genreRepository->find($id);

        if (!$genre) {
            return $this->json(['error' => 'Genre not found'], 404);
        }

        $this->entityManager->remove($genre);
        $this->entityManager->flush();

        return $this->json(['message' => 'Genre deleted successfully']);
    }
}
