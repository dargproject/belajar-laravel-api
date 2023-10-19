<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $check_login = auth()->attempt(
            $request->only('email', 'password')
        );

        if (! $check_login) {
            return response()->json([
                'status' => false,
                'error' => 'Email atau Password Tidak cocok',
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'error' => 'Login Berhasil',
            'data' => [
                'token' => $check_login,
            ],
        ]);
    }
}
