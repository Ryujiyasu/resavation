<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'CalendarController@index')->name('calendar');
Route::post('/', 'CalendarController@book')->name('calendarbook');

Auth::routes();

Route::get('schedule/getData', 'CalendarController@scheduleGet');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/getMember', 'CalendarController@memberGet');

Route::get('schedule/listing/', 'HomeController@index');
Route::get('schedule/getDataJson/', 'HomeController@getDataJson');
