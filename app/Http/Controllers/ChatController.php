<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'string|in:user,bot',
            'history.*.content' => 'string',
        ]);

        try {
            $geminiKey = env('GEMINI_API_KEY');
            
            if (!$geminiKey) {
                return response()->json([
                    'success' => false,
                    'error' => 'API key not configured. Please add GEMINI_API_KEY to your .env file.',
                ], 500);
            }

            // Get games from database to provide context
            $games = Game::with('admin')->get()->map(function($game) {
                return [
                    'title' => $game->game_title,
                    'rating' => $game->rating,
                    'review' => substr($game->review_text, 0, 200) . '...',
                    'reviewer' => $game->admin->name ?? 'Anonymous'
                ];
            })->toArray();

            $gamesContext = "Here are the games reviewed on PlayScore:\n\n";
            foreach ($games as $game) {
                $gamesContext .= "- {$game['title']} (Rating: {$game['rating']}/100) - Reviewed by {$game['reviewer']}\n";
            }

            // Build conversation history for Gemini
            $contents = [];
            
            // Add system message as first user message (Gemini doesn't have system role)
            $contents[] = [
                'role' => 'user',
                'parts' => [
                    ['text' => "You are an expert gaming assistant for PlayScore, a professional game review website. You have access to our reviewed games database. When users ask for recommendations, prioritize games from our reviews. Provide helpful, enthusiastic recommendations and insights. Be concise but informative.\n\n{$gamesContext}"]
                ]
            ];
            
            $contents[] = [
                'role' => 'model',
                'parts' => [
                    ['text' => "Hi! I'm your gaming assistant. I have access to all the games reviewed on PlayScore. Ask me anything about games, recommendations, or reviews! dont exceed 30 words in your response."]
                ]
            ];

            // Add conversation history
            $history = $request->input('history', []);
            foreach ($history as $msg) {
                // Map 'bot' to 'model' for Gemini API
                $role = $msg['role'] === 'bot' ? 'model' : 'user';
                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => $msg['content']]
                    ]
                ];
            }

            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key={$geminiKey}",
                [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.9,
                        'maxOutputTokens' => 300,
                        'topP' => 0.95,
                        'topK' => 40,
                    ]
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);
            } else {
                \Log::error('Gemini API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to get response from AI.',
                    'details' => config('app.debug') ? $response->body() : null,
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Chat Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to get response from AI. Please try again.',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
