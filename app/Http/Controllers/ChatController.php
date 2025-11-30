<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful gaming assistant for PlayScore, a game review website. Help users find game recommendations, answer questions about games, and provide gaming advice. Keep responses concise and friendly.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->input('message')
                    ],
                ],
                'max_tokens' => 200,
            ]);

            return response()->json([
                'success' => true,
                'reply' => $result->choices[0]->message->content,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get response from AI. Please try again.',
            ], 500);
        }
    }
}
