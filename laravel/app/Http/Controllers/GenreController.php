<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    // GET /api/genres - список усіх жанрів
    public function index(): JsonResponse
    {
        return response()->json(Genre::all());
    }

    // GET /api/genres/{id} - один жанр
    public function show(int $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['error' => 'Genre not found'], 404);
        }

        return response()->json($genre);
    }

    // POST /api/genres - створити жанр
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $genre = Genre::create($validated);

        return response()->json($genre, 201);
    }

    // PATCH /api/genres/{id} - оновити жанр
    public function update(Request $request, int $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['error' => 'Genre not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $genre->update($validated);

        return response()->json($genre);
    }

    // DELETE /api/genres/{id} - видалити жанр
    public function destroy(int $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['error' => 'Genre not found'], 404);
        }

        $genre->delete();

        return response()->json(['message' => 'Genre deleted successfully']);
    }
}
