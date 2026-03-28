<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIChatService;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    /**
     * Handle the chat query input and return an AI response.
     */
    public function ask(Request $request, AIChatService $chatService)
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

        // 1. Embed the question
        $questionEmbedding = $chatService->getEmbedding($question);
        
        if (empty($questionEmbedding)) {
            return response()->json(['error' => 'Failed to process question via AI. Please check API keys.'], 500);
        }

        // 2. Query Pinecone
        $matches = $chatService->queryPinecone($questionEmbedding, 3);
        
        $context = "";
        foreach ($matches as $match) {
            $context .= ($match['metadata']['text'] ?? '') . "\n\n";
        }

        // 3. Get Answer from Gemini
        $answer = $chatService->chatWithContext($question, $context);

        // Cache the response for 1 hour to save API quota on repeated similar queries
        if ($answer !== "Sorry, I encountered an error while trying to answer your question. Please try again later." && $answer !== "AI services are not configured.") {
            Cache::put($cacheKey, $answer, now()->addHour());
        }

        return response()->json([
            'answer' => $answer,
            'cached' => false
        ]);
    }
}
