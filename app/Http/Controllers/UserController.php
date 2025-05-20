<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    /**
     * Mostrar todos los usuarios (solo ADMIN).
     */

    public function index() {
        if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
            return response()->json(['message' => 'No eres admin para ver todos los usuarios'], 403);
        }

        // Obtener todos los usuarios con sus roles
        $users = User::with('roles')->get();
        // Transformar la respuesta para incluir los nombres de los roles
        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'puntos' => $user->puntos,
                'role' => $user->roles->pluck('name')->first() // Obtener solo el primer rol
            ];
        });

        return response()->json($users, 200);
    }



    /**
     * Crear un usuario (solo ADMIN).
     */
    public function store(Request $request) {
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para crear un usuario'], 403);
          }
        /*
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                    'puntos' => 'integer|min:0',
                    'role' => 'required|string|exists:roles,name'
                ]);
        */

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'puntos' => $request->puntos ?? 0,
                ]);

                $user->assignRole($request->role);

                return response()->json($user, 201);
            }

            /**
             * Mostrar un usuario específico (solo ADMIN).
             */
    public function show(string $id) {
        if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
            return response()->json(['message' => 'No eres admin para ver este  usuario'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Actualizar un usuario (solo ADMIN).
     */
    public function update(Request $request, string $id) {
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
             return response()->json(['message' => 'No eres admin para editar este  usuarios'], 403);
          }

        $user = User::findOrFail($id);

       /* $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'puntos' => 'sometimes|integer|min:0',
            'role' => 'sometimes|string|exists:roles,name'
        ]);
       */

        $user->update($request->only(['name', 'email', 'puntos']));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json($user, 200);
    }

    /**
     * Eliminar un usuario (solo ADMIN).
     */
    public function destroy(string $id) {
          if (!auth()->user() || !auth()->user()->hasRole('ADMIN')) {
            return response()->json(['message' => 'No eres admin para ver borrar este usuario'], 403);
          }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }


    //Metodo para registrar usuarios
    public function register(Request $request)
    {
        // Validar los datos del usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:2|confirmed',
        ]);

        // Crear el usuario con los datos proporcionados
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'puntos' => 1000, // Se le asignan 1000 puntos de inicio
        ]);

        // Asignar el rol por defecto
        $user->assignRole('USER');

        // Disparar evento de registro para enviar email de verificación
             event(new Registered($user));


        // Verificar si el usuario ha verificado su correo
     //   if (!$user->hasVerifiedEmail()) {
     //       return response()->json(['message' => 'Debes verificar tu correo electrónico antes de iniciar sesión.'], 403);
     //   }

        // Generar un token de acceso con Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    //Ver puntos usuario
    public function getUserPoints($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['puntos' => $user->puntos], 200);
    }


    // Cambiar los puntos
    public function actualizarPuntos(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'puntos' => 'required|integer|min:0'
        ]);

        $user->puntos = $request->puntos;
        $user->save();

        return response()->json(['message' => 'Puntos actualizados correctamente', 'puntos' => $user->puntos], 200);
    }
}
