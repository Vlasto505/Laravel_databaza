<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    // REGISTRACIJA
    public function register(Request $request) {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:128'],
            'last_name'  => ['required', 'string', 'min:2', 'max:128'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => [
                'required',
                'confirmed',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => $validated['password'], // Cast u User modelu će ga sam hešovati
            'role'       => 'user',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrácia prebehla úspešne.',
            'user'    => $user,
            'token'   => $token,
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Nesprávny email alebo heslo.'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response([
            'message' => 'Prihlásenie bolo úspešné.',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Používateľ bol odhlásený z aktuálneho zariadenia.',
        ], Response::HTTP_OK);
    }
    public function me(Request $request) {
        return response()->json([
        'user' => $request->user(),
        'active_sessions' => $request->user()->tokens()->count(),
        ], Response::HTTP_OK);
    }
    public function logoutAll(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Boli ste odhlásení zo všetkých zariadení.'
        ], 200);
    }
    public function changePassword(Request $request) {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Heslo bolo úspešne zmenené.'
        ], 200);
    }
    public function updateProfile(Request $request) {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Niste ulogovani'], 401);
        }

        $fields = $request->validate([
            'first_name' => 'sometimes|required|string|min:2|max:128',
            'last_name'  => 'sometimes|required|string|min:2|max:128',
        ]);

        $user->update($fields);

        return response()->json([
            'message' => 'Profil bol aktualizovaný.',
            'user' => $user
        ], 200);
    }
}
