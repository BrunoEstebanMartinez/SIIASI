<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

<<<<<<< HEAD
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
=======
Route::post('nota/nuevo/openia/{RESPONSE}', 'notaperiodisticaController@openAIAPI');
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
