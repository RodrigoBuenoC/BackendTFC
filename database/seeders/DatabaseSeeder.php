<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();
         Game::factory()->create([
             'name' => 'Penalty Shoot-out',
             'descripcion' => 'Penalty Shoot-out es un juego de casino basado en la emoción de los tiros penales. Apuesta, elige tu tiro y desafía al portero para ganar grandes premios. ¡Pon a prueba tu suerte y precisión!',
         ]);
        Game::factory()->create([
            'name' => 'Plinko',
            'descripcion' => 'Plinko es un emocionante juego de casino donde dejas caer una ficha desde la parte superior de un tablero y observas cómo rebota entre clavijas hasta aterrizar en un premio. ¡Apuesta, juega y gana al azar',
        ]);
        Game::factory()->create([
            'name' => 'Tragaperras',
            'descripcion' => 'Disfruta de la emoción y la diversión con nuestra tragaperras, llena de símbolos coloridos y grandes premios. Gira los carretes y prueba tu suerte para conseguir combinaciones ganadoras que te harán ganar a lo grande. ',
        ]);
        Game::factory()->create([
            'name' => 'Mines',
            'descripcion' => 'Buscaminas Casino es un emocionante juego de estrategia y suerte donde cada clic puede hacerte ganar o perder. Explora un tablero de 5x5 celdas buscando minas escondidas.'
        ]);


         GameSession::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);
    }
}
