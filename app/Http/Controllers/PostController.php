<?php

namespace App\Http\Controllers;

use App\Entities\Post;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index()
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
        return response()->json(['data' => $posts]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required'
            ]);

            $post = new Post();
            $post->setTitle($validated['title']);
            $post->setContent($validated['content']);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return response()->json([
                'message' => 'Post created successfully',
                'data' => [
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'content' => $post->getContent()
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        $post = $this->entityManager->find(Post::class, $id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['data' => [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ]]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $post = $this->entityManager->find(Post::class, $id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post->setTitle($validated['title']);
        $post->setContent($validated['content']);
        
        $this->entityManager->flush();

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent()
            ]
        ]);
    }

    public function destroy(int $id)
    {
        $post = $this->entityManager->find(Post::class, $id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return response()->noContent();
    }
} 