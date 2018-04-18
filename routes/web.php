<?php

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
// used for regular expression for all routes ...
Route::patterns(['id'=>'[0-9]+','search_item'=>'[A-Za-z]+']);

Auth::routes();
Route::get('/dash','EmployeeController@index')->name('dashBoard')->middleware('auth');
Route::get('/', 'HomeController@welcome')->name('welcome')->middleware('auth');
Route::get('/home', 'EmployeeController@index')->name('home');

Route::get('employee/add','EmployeeController@create');
Route::post('employee/store','EmployeeController@store');

Route::get('employee/edit/{id}', 'EmployeeController@edit');
Route::get('employee/update','EmployeeController@update');

Route::get('employee/search', 'EmployeeController@search');
Route::get('employee/show', 'EmployeeController@show');

Route::get('employee/delete/{id}','EmployeeController@destroy');
