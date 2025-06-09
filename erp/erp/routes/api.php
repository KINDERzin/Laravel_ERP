<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;

/*
|--------------------------------------------------------|
| Rotas da API                                           |
|--------------------------------------------------------|
*/

//ROTAS DE AUTENTICAÇÃO (SEM MIDDLEWATRE)
Route::prefix('auth')->group(function(){
    // Rotas públicas (rotas que não precisam de TOKEN)                         // SEM TOKEN
    Route::post('/register', [AuthController::class, 'Register']);              // POST   -  api/auth/register
    Route::post('/login', [AuthController::class, 'Login']);                    // POST   -  api/auth/login
});                                                                             //
                                                                                //
Route::middleware('auth:sanctum')->group(function(){                            //
                                                                                //
    // Rotas de autenticação que precisa de TOKEN                               //
    Route::prefix('auth')->group(function(){                                    // COM TOKEN
        Route::post('/logout', [AuthController::class, 'Logout']);              // POST   -  api/auth:sanctum/auth/logout
        Route::get('/user', [AuthController::class, 'User']);                   // GET    -  api/auth:sanctum/auth/user
    });                                                                         //
                                                                                //
    // Rota do usuário (a que vem com o arquivo)                                //
    // Route::get('/user', function (Request $request) {                        //
    //     return $request->user();                                             //
    // })->middleware('auth:sanctum');                                          //
                                                                                //
    // ROTAS DOS CLIENTES                                                       //
    Route::prefix('clientes')->group(function(){                                // 
        Route::get('/', [ClienteController::class, 'FindAll']);                 // GET    -  api/clientes/
        Route::get('/{$id}', [ClienteController::class, 'FindOne']);            // GET    -  api/clientes/id
        Route::post('/create', [ClienteController::class, 'Create']);           // POST   -  api/clientes/create
        Route::put('/update-{$id}', [ClienteController::class, 'Update']);      // PUT    -  api/clientes/update-id
        Route::delete('/delete-{$id}', [ClienteController::class, 'Delete']);   // DELETE -  api/clientes/delete-id
    });
});



