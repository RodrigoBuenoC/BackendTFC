<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Muestra la lista de todos los juegos disponibles.
     *
     * @return \Illuminate\Http\JsonResponse Devuelve un JSON con la lista de juegos o un mensaje si no hay juegos disponibles.
     */

    // Ver Juegos
    public function index()
    {

        $games = Game::all();

        if ($games->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron juegos.'
            ], 404);
        }

        return response()->json($games);
    }

    /**
     * Crea un nuevo juego (Solo permitido para ADMIN).
     *
     * @param  Request  $request Contiene los datos del nuevo juego a crear.
     * @return \Illuminate\Http\JsonResponse Devuelve el juego creado o un mensaje de error si el usuario no es admin.
     */

    // Crear un Juego (Solo ADMIN)
    public function store(Request $request)
    {
        // Verifica si el usuario está autenticado y si tiene el rol de ADMIN
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
              return response()->json(['message' => 'No eres admin para crear un juego'], 403);
           }

        // Crea el juego con los datos proporcionados
        $game = Game::create([
            'name' => $request['name'],
            'descripcion' => $request['descripcion'],
        ]);

        return response()->json($game, 201);
    }

    /**
     * Muestra los detalles de un juego específico.
     *
     * @param  string  $id ID del juego a consultar.
     * @return \Illuminate\Http\JsonResponse Devuelve los detalles del juego o un mensaje de error si no se encuentra.
     */

    // Ver un Juego
    public function show(string $id)
    {
        // Verifica si el usuario tiene permiso (ADMIN o USER)
        if (!auth()->user() || !auth()->user()->hasRole(['ADMIN','USER'])) {
            return response()->json(['message' => 'No eres admin para ver este juego'], 403);
        }
        $game = Game::find($id);

        if (!$game) {
            return response()->json(['error' => 'Juego no encontrado'], 404);
        }

        return response()->json($game, 200);
    }

    /**
     * Actualiza la información de un juego existente (Solo ADMIN).
     *
     * @param  Request  $request Contiene los nuevos datos del juego.
     * @param  string  $id ID del juego a actualizar.
     * @return \Illuminate\Http\JsonResponse Devuelve el juego actualizado o un mensaje de error si no se encuentra o no tiene permisos.
     */

    // Editar Juego (Solo ADMIN)
    public function update(Request $request, string $id)
    {
        // Verifica si el usuario es ADMIN
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para editar este juego'], 403);
          }
        $game = Game::findOrFail($id);

        // Actualiza el juego con los nuevos datos
        $game->update([
            'name' => $request['name'],
            'descripcion' => $request['descripcion'],
        ]);

        return response()->json($game, 200);
    }

    /**
     * Elimina un juego (Solo ADMIN).
     *
     * @param  string  $id ID del juego a eliminar.
     * @return \Illuminate\Http\JsonResponse Devuelve un mensaje de confirmación o error si no se encuentra o no tiene permisos.
     */

    // Borrar Juego (Solo ADMIN)
    public function destroy(string $id)
    {
        // Verifica si el usuario es ADMIN
        if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
            return response()->json(['message' => 'No eres admin para borrar este juego'], 403);
          }

        $game = Game::find($id);

        if (!$game) {
            return response()->json(['error' => 'Juego no encontrado'], 404);
        }

        // Elimina el juego
        $game->delete();

        return response()->json(['message' => 'Juego borrado correctamente'], 200);
    }
}
