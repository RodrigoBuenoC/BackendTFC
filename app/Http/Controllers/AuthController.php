<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    /**
     * Inicia sesión de un usuario autenticándolo con email y contraseña.
     *
     * @param  Request  $request  La solicitud HTTP con las credenciales del usuario.
     * @return \Illuminate\Http\JsonResponse  Devuelve el token de acceso si la autenticación es exitosa.
     */

    public function login(Request $request)
    {

        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar el usuario por email e incluir la relación con roles
        $user = User::where('email', $request->email)->with('roles')->first();

        // Verificar si el usuario existe y si la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear un token de autenticación para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolver la respuesta con el token y los datos del usuario
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'puntos' => $user->puntos,
                'role' => $user->roles->pluck('name')->first() // Obtener el primer rol del usuario
            ]
        ], 200);
    }

    /**
     * Cierra la sesión del usuario actual eliminando sus tokens de autenticación.
     *
     * @param  Request  $request  La solicitud HTTP del usuario autenticado.
     * @return \Illuminate\Http\JsonResponse  Devuelve un mensaje de confirmación.
     */
    public function logout(Request $request)
    {
        // Revoca todos los tokens del usuario autenticado
        $request->user()->tokens()->delete();

        // Responder con un mensaje de éxito
        return response()->json(['message' => 'Sesión cerrada'], 200);
    }


}
