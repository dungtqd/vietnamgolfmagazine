<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//program
//Route::post('/program/create', 'ProgramController@store');
//Route::put('/program/update/{id}', 'ProgramController@update');
//Route::patch('/program/update/{id}', 'ProgramController@update');
Route::get('/program/get-by-id/{id}', 'ProgramController@show');
Route::get('/program/all', 'ProgramController@index');
Route::get('/program/get-root-program/{id}', 'ProgramController@getRootProgram');
Route::post('/program/get-detail-root-program', 'ProgramController@getDetailRootProgram');
Route::post('/program/get-children-program', 'ProgramController@getChildrenProgram');
Route::post('/program/get-detail-children-program', 'ProgramController@getDetailChildProgram');


//vote
Route::get('/vote/count-total', 'VoteController@countTotalVote');
//banner
Route::get('/banner/get-root-banner/{languageId}', 'BannerController@getAll');
