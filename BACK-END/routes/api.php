<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\UserController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//vamos a generar la ruta
/**La primera ruta es una ruta de POST que apunta a "/register" y llama al método "register" 
 * en el controlador UserController. Esta ruta se utiliza para registrar un nuevo usuario en la aplicación. */
Route::controller(UserController::Class)->group(function(){
// momentos: get,post,put,delete
//nombre de la ruta ('/register')--nombre de la funcion('register')
    Route::post('/register','register');
//nombre de la ruta ('/login')--nombre de la funcion('login')
    Route::post('/login','login');    
    
});


// Aqui es donde vamos a validar que ya exista un token de login, la ruta es la del auth controler donde esta el servicio
Route::middleware('auth:sanctum')->delete('/logout', [UserController::class, 'logout']);

// generamos la ruta del UserController
//la primera ruta puede ser como yo quiera 
Route::get('/users/show/{id}', [UserController::class,'showById']);



Route::put('/newPassword/{email}', [UserController::class,'newPassword']);

