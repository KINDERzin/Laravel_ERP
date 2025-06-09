<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use LDAP\Result;

class AuthController extends Controller
{
    // Cria um Usuário novo
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'succes' => true,
            'message' => 'Usuário registrado com sucesso!',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
            ], Response::HTTP_CREATED);
    }

    // Login do usuário
    public function Login(Request $request)
    {
        // Valida os dados
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos!',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
    
        // Verifica as credenciais 
        if(!Auth::attempt($request->only('email', 'password')))
            return response()->json([
                'success' => false,
                'message' => 'Crecenciais inválidas.'
            ], Response::HTTP_UNAUTHORIZED);

        // Busca o usuário e gera o token
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    // Logout do usuário
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso.'
        ]);
    }

    public function User(Request $request)
    {
        return  response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
