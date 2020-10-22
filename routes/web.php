<?php

use Illuminate\Support\Facades\Route;


Route::get('/getMySchedule/{id}', 'CalendarController@mySchedule')->name('mySchedule');
Route::get('/', 'CalendarController@index')->name('calendar');
Route::post('/', 'CalendarController@book')->name('calendarbook');

Auth::routes();

Route::get('schedule/getData', 'CalendarController@scheduleGet');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/getMember', 'CalendarController@memberGet');

Route::get('schedule/listing/', 'HomeController@index');
Route::get('schedule/getDataJson/', 'HomeController@getDataJson');


Route::get('schedule/edit/{id}', 'HomeController@editSchedule');
Route::post('schedule/edit/{id}', 'HomeController@editScheduleComplete');

Route::get('/listing', 'HomeController@listing');
