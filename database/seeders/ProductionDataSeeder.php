<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     * Imports all existing users and games from the database
     */
    public function run(): void
    {
        // Get all existing users and re-seed them
        $users = User::all();
        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'password' => $user->password,
                    'is_admin' => $user->is_admin ?? false,
                    'remember_token' => $user->remember_token,
                ]
            );
        }

        // Get all existing games and re-seed them
        $games = Game::all();
        foreach ($games as $game) {
            Game::firstOrCreate(
                ['rawg_id' => $game->rawg_id],
                [
                    'game_title' => $game->game_title,
                    'rating' => $game->rating,
                    'review_text' => $game->review_text,
                    'admin_id' => $game->admin_id,
                ]
            );
        }

        echo "✓ Seeded " . count($users) . " users\n";
        echo "✓ Seeded " . count($games) . " games\n";
    }
}
