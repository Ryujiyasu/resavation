<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CalendarController@index')->name('calendar');
Route::post('/', 'CalendarController@book')->name('calendarbook');

Auth::routes();

Route::get('schedule/getData', 'CalendarController@scheduleGet');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/getMember', 'CalendarController@memberGet');

Route::get('schedule/listing/', 'HomeController@index');
