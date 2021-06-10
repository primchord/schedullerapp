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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return 'Hello World!';
});

// テスト用API
Route::get('/getusers', 'UsersController@getUsers');
Route::get('/getall', 'SchedulesController@getAll');
Route::get('/agecount', 'UsersController@ageCount');

//データ取得
Route::get('/getschedules', 'SchedulesController@getSchedules');

//データ削除
Route::get('/delete/{id}', 'SchedulesController@delete');

//データ登録
Route::post('/addschedule', 'SchedulesController@addSchedule');

//データ更新
Route::post('/update', 'SchedulesController@update');
