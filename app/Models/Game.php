<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $primaryKey = 'game_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rawg_id',
        'game_title',
        'rating',
        'review_text',
        'admin_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'rawg_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (admin) who created this game review.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    /**
     * Get comments for this game.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'game_id', 'game_id');
    }
}
