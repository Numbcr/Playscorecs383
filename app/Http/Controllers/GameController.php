<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    private $rawgApiKey;

    public function __construct()
    {
        $this->rawgApiKey = config('services.rawg.api_key');
    }

    /**
     * Get popular games (highest rated).
     */
    public function getPopular(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $offset = ($page - 1) * $limit;

        // Get all games, sorted by rating
        $games = Game::with('admin')
            ->orderByDesc('rating')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(fn($game) => $this->enrichGameData($game));

        return response()->json([
            'success' => true,
            'data' => $games,
        ]);
    }

    /**
     * Get recent games.
     */
    public function getRecent(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $offset = ($page - 1) * $limit;

        $games = Game::with('admin')
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(fn($game) => $this->enrichGameData($game));

        return response()->json([
            'success' => true,
            'data' => $games,
        ]);
    }

    /**
     * Search for games.
     */
    public function search(Request $request)
    {
        $query = $request->query('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'error' => 'Search query is required',
            ], 400);
        }

        $games = Game::with('admin')
            ->where('game_title', 'like', "%{$query}%")
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($game) => $this->enrichGameData($game));

        return response()->json([
            'success' => true,
            'data' => $games,
        ]);
    }

    /**
     * Get a specific game review by ID.
     */
    public function show($id)
    {
        $game = Game::with('admin')->find($id);

        if (!$game) {
            return response()->json([
                'success' => false,
                'error' => 'Review not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'review' => [
                'game_id' => $game->game_id,
                'rawg_id' => $game->rawg_id,
                'game_title' => $game->game_title,
                'rating' => $game->rating,
                'review_text' => $game->review_text,
                'created_at' => $game->created_at,
                'username' => $game->admin?->name ?? 'Anonymous',
                'user_id' => $game->admin?->id,
            ],
        ]);
    }

    /**
     * Create a new game review (admin only).
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'rawgId' => 'nullable|integer',
            'game_title' => 'required|string',
            'rating' => 'required|integer|min:1|max:100',
            'review_text' => 'required|string',
        ]);

        try {
            $game = Game::create([
                'rawg_id' => $request->rawgId,
                'game_title' => $request->game_title,
                'rating' => $request->rating,
                'review_text' => $request->review_text,
                'admin_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'insertId' => $game->game_id,
                'gameTitle' => $request->game_title,
                'message' => 'Review added successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a game review (admin only).
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'success' => false,
                'error' => 'Review not found',
            ], 404);
        }

        if ($game->admin_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to update this review',
            ], 403);
        }

        $request->validate([
            'game_title' => 'required|string',
            'rating' => 'required|integer|min:1|max:100',
            'review_text' => 'required|string',
        ]);

        $game->update([
            'game_title' => $request->game_title,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
        ]);
    }

    /**
     * Delete a game review (admin only).
     */
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'success' => false,
                'error' => 'Review not found',
            ], 404);
        }

        if ($game->admin_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to delete this review',
            ], 403);
        }

        $game->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }

    /**
     * Get RAWG API key.
     */
    public function getRawgApiKey()
    {
        return response()->json([
            'success' => true,
            'key' => $this->rawgApiKey,
        ]);
    }

    /**
     * Enrich game data with RAWG API information.
     */
    private function enrichGameData($game)
    {
        $game->game_image = '';

        // Only fetch RAWG data if rawg_id exists
        if ($game->rawg_id) {
            try {
                $rawgResponse = Http::timeout(5)->get("https://api.rawg.io/api/games/{$game->rawg_id}", [
                    'key' => $this->rawgApiKey,
                ]);

                if ($rawgResponse->successful()) {
                    $rawgData = $rawgResponse->json();
                    $game->game_image = $rawgData['background_image'] ?? '';
                }
            } catch (\Exception $e) {
                // Log error if needed, but don't fail the request
                \Log::warning('RAWG API error for game ' . $game->rawg_id . ': ' . $e->getMessage());
            }
        }

        return [
            'game_id' => $game->game_id,
            'rawg_id' => $game->rawg_id,
            'game_title' => $game->game_title,
            'rating' => $game->rating,
            'review_text' => $game->review_text,
            'created_at' => $game->created_at,
            'admin_username' => $game->admin?->name ?? 'Unknown',
            'game_image' => $game->game_image,
        ];
    }
}
