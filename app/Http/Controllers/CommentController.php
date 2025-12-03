<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Get comments for a specific game
    public function index($gameId)
    {
        $comments = Comment::with('user')
            ->where('game_id', $gameId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'username' => $comment->user->name,
                    'user_id' => $comment->user_id,
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    // Add a comment
    public function store(Request $request, $gameId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to comment.'
            ], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'game_id' => $gameId,
            'comment' => $request->comment
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'username' => $comment->user->name,
                'user_id' => $comment->user_id,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    // Delete a comment
    public function destroy($gameId, $commentId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $comment = Comment::where('id', $commentId)
            ->where('game_id', $gameId)
            ->first();

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found.'
            ], 404);
        }

        // Only the comment owner or admin can delete
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
}
