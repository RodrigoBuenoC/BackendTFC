<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class GameSession extends Model

{
    use HasFactory, Notifiable ,HasApiTokens, HasRoles;

    protected $fillable = [
        'user_id',
        'game_id',
        'puntos_ganados',
    ];
    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
