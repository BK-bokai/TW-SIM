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


Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::post('register', 'Auth\RegisterController@register')->name('do_register');

Route::get('confirm/{active}','Auth\RegisterController@confirm')->name('confirm');

Route::get('/email', 'frontend\RegisterEmailController@send');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login')->name('do_login');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/Facebook/redirect/{provider}', 'FbLoginController@redirect')->name('fbLogin');
Route::get('/FBcallback/{provider}', 'FbLoginController@callback');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/Evaluate','backend\EvaluateController@index')->name('Evaluate');
    Route::post('/Evaluate', 'backend\EvaluateController@evaluate')->name('do_Evaluate');

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    
});
