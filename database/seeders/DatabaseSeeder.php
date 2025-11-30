<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for testing
        $admin = User::firstOrCreate(
            ['email' => 'admin@playscorecs383.test'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Create regular user for testing
        User::firstOrCreate(
            ['email' => 'test@playscorecs383.test'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // Create sample games with reviews
        Game::firstOrCreate(
            ['rawg_id' => 3498],
            [
                'game_title' => 'Grand Theft Auto V',
                'rating' => 92,
                'review_text' => 'An absolutely phenomenal game with incredible open-world design and engaging storyline. The graphics are stunning and the gameplay is smooth.',
                'admin_id' => $admin->id,
            ]
        );

        Game::firstOrCreate(
            ['rawg_id' => 4200],
            [
                'game_title' => 'The Witcher 3: Wild Hunt',
                'rating' => 88,
                'review_text' => 'A masterpiece of RPG gaming with a compelling narrative and diverse side quests. The character development is exceptional.',
                'admin_id' => $admin->id,
            ]
        );

        Game::firstOrCreate(
            ['rawg_id' => 3328],
            [
                'game_title' => 'Red Dead Redemption 2',
                'rating' => 95,
                'review_text' => 'One of the finest open-world experiences ever created. The attention to detail is mind-blowing and the story is unforgettable.',
                'admin_id' => $admin->id,
            ]
        );

        Game::firstOrCreate(
            ['rawg_id' => 5286],
            [
                'game_title' => 'Halo: Combat Evolved',
                'rating' => 85,
                'review_text' => 'A legendary shooter that defined the genre. Great level design and an engaging campaign.',
                'admin_id' => $admin->id,
            ]
        );

        Game::firstOrCreate(
            ['rawg_id' => 32],
            [
                'game_title' => 'Destiny',
                'rating' => 78,
                'review_text' => 'An ambitious online shooter with good mechanics but repetitive content. Still worth playing with friends.',
                'admin_id' => $admin->id,
            ]
        );
    }
}
