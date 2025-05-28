<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    /**
     * Muestra todas las sesiones de juego.
     *
     * @return \Illuminate\Http\JsonResponse Devuelve un JSON con la lista de sesiones de juego o un mensaje si no hay ninguna.
     */

    //Ver sesion de juego
    public function index()
    {
            if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para ver las sesiones de juego'], 403);
          }

        $session = GameSession::all();

        // Verifica si hay sesiones registradas
        if ($session->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron sesiones.'
            ], 404);
        }

        return response()->json($session);
    }

    /**
     * Crea una nueva sesión de juego.
     *
     * @param  Request  $request Contiene los datos de la nueva sesión de juego.
     * @return \Illuminate\Http\JsonResponse Devuelve la sesión creada en JSON con código 201 (Created).
     */
    //Crear sesion de juego
    public function store(Request $request)
    {
           if (!auth()->user() || !auth()->user()->hasRole(['ADMIN','USER'])) {
             return response()->json(['message' => 'No eres admin para crear la sesion de juego'], 403);
          }
        $session = GameSession::create([
            'user_id' => $request->input('user_id'),
            'game_id' => $request->input('game_id'),
            'puntos_ganados' => $request->input('puntos_ganados'),
        ]);

        return response()->json($session, 201);
    }

    /**
     * Muestra los detalles de una sesión de juego específica.
     *
     * @param  string  $id ID de la sesión de juego.
     * @return \Illuminate\Http\JsonResponse Devuelve los detalles de la sesión o un error si no se encuentra.
     */

    //Ver sesion de juego
    public function show(string $id)
    {
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para ver la sesion de juego'], 403);
          }
        $session = GameSession::find($id);

        if (!$session) {
            return response()->json(['error' => 'Sesion no encontrado'], 404);
        }

        return response()->json($session, 200);
    }

    /**
     * Actualiza una sesión de juego existente.
     *
     * @param  Request  $request Contiene los nuevos datos de la sesión.
     * @param  string  $id ID de la sesión a actualizar.
     * @return \Illuminate\Http\JsonResponse Devuelve la sesión actualizada o un mensaje de error si no se encuentra.
     */

    //Editar sesion de juego
    public function update(Request $request, string $id)
    {
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para editar las sesion de juego'], 403);
         }
        // Buscar la sesion por ID
        $session = GameSession::findOrFail($id);

        // Actualizar los datos de la sesion
        $session->update([
            'user_id' => $request->input('user_id'),
            'game_id' => $request->input('game_id'),
            'puntos_ganados' => $request->input('puntos_ganados'),
        ]);

        return response()->json($session, 200);
    }

    /**
     * Elimina una sesión de juego.
     *
     * @param  string  $id ID de la sesión a eliminar.
     * @return \Illuminate\Http\JsonResponse Devuelve un mensaje de confirmación o un error si no se encuentra.
     */
    //Borrar sesion de juego
    public function destroy(string $id)
    {
         if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para borrar la sesion de juego'], 403);
         }
        $session = GameSession::find($id);

        if (!$session) {
            return response()->json(['error' => 'Sesion no encontrado'], 404);
        }

        $session->delete();

        return response()->json(['message' => 'Sesion borrado correctamente'], 200);
    }


    /**
     * Obtiene todas las sesiones de juego de un usuario autenticado y calcula la suma de sus puntos ganados.
     *
     * @return \Illuminate\Http\JsonResponse Devuelve la lista de sesiones del usuario y la suma total de los puntos ganados.
     */
    //Sesiones de juego de cada usuario
    public function mySessions()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtiene y transforma las sesiones
        $sessions = GameSession::where('user_id', $user->id)->get()->map(function ($session) {
            return [
                'id' => $session->id,
                'game_id' => $session->game_id,
                'puntos_ganados' => $session->puntos_ganados,
                'fecha_sesion' => $session->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $totalPuntos = $sessions->sum('puntos_ganados');

        return response()->json([
            'sessions' => $sessions,
            'puntos_totales' => $totalPuntos
        ], 200);
    }



}
