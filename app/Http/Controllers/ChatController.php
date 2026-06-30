<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIChatService;
use App\Exceptions\AIChatException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    /**
     * Handle the chat query input and return an AI response.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \App\Services\AIChatService $chatService The AI chat service.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the answer.
     */
    public function ask(Request $request, AIChatService $chatService): JsonResponse
    {
        $request->validate([
            'question' => 'required|string|max:1000'
        ]);

        $question = trim($request->input('question'));

        // Caching: generate a unique cache key based on the sanitized question
        $cacheKey = 'ai_chat_response_' . md5(strtolower($question));

        $cachedResponse = Cache::get($cacheKey);
        if ($cachedResponse) {
            return response()->json([
                'answer' => $cachedResponse,
                'cached' => true
            ]);
        }

        try {
            // 1. Get similar context from Pinecone using AIChatService
            $context = $chatService->getContextForQuestion($question);

            // 2. Get Answer from Gemini using the context
            $answer = $chatService->chatWithContext($question, $context);

            // Cache the response for 1 hour to save API quota on repeated similar queries
            Cache::put($cacheKey, $answer, now()->addHour());

            return response()->json([
                'answer' => $answer,
                'cached' => false
            ]);
        } catch (AIChatException $e) {
            $message = $e->getMessage();
            $errorType = 'generic';

            if (str_contains($message, 'Quota') || str_contains($message, 'quota')) {
                $errorType = 'quota';
            } elseif (str_contains($message, 'busy') || str_contains($message, 'demand') || str_contains($message, 'Unavailable') || str_contains($message, 'unavailable') || str_contains($message, 'busy_server')) {
                $errorType = 'busy_server';
            } elseif (str_contains($message, 'token') || str_contains($message, 'Token')) {
                $errorType = 'tokens';
            }

            return response()->json([
                'error' => $message,
                'error_type' => $errorType
            ], 500);
        }
    }
}
