<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $geminiKey = env('GEMINI_API_KEY');
            
            if (!$geminiKey) {
                return response()->json([
                    'success' => false,
                    'error' => 'API key not configured. Please add GEMINI_API_KEY to your .env file.',
                ], 500);
            }

            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key={$geminiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "You are an expert gaming assistant for PlayScore, a professional game review website. You have deep knowledge of video games across all platforms, genres, and eras. Provide helpful, enthusiastic recommendations and insights. Be concise but informative. When recommending games, mention specific titles and why they're good. Keep responses under 100 words.\n\nUser question: " . $request->input('message')
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.9,
                        'maxOutputTokens' => 150,
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
