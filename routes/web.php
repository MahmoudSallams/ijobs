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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate');

Route::resource('pages', 'PageController');

Route::resource('profiles', 'ProfileController');

Route::resource('jobs', 'JobController');

Route::resource('groups', 'GroupController');

Route::resource('contacts', 'ContactController');

Route::resource('companies', 'CompanyController');

Route::resource('userCompanies', 'UserCompanyController');

Route::resource('groupUsers', 'GroupUserController');

Route::resource('groupJobs', 'GroupJobController');

Route::resource('jobUsers', 'JobUserController');