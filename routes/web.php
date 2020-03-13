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
    return redirect(route('login'));
});

Route::get('/change_Path', 'change_Path_controller@index');
Route::get('test/', function () {
    return view('test');
});


Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::post('register', 'Auth\RegisterController@register')->name('do_register');

Route::get('confirm/{active}', 'Auth\RegisterController@confirm')->name('confirm');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login')->name('do_login');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/Facebook/redirect/{provider}', 'Auth\FbLoginController@redirect')->name('fbLogin');
Route::get('/FBcallback/{provider}', 'Auth\FbLoginController@callback');

// https://192.168.1.166/php/TW_SIM_Evaluate/public/FBcallback/facebook

Route::middleware('auth')->prefix('admin')->name('Met.')->group(function () {
    Route::namespace('Meteorology')->group(function () {
        Route::get('/Evaluate', 'EvaluateController@index')->name('Evaluate');
        Route::get('/Evaluate/data/{Met_evaluates}', 'EvaluateController@detail')->name('detail_Evaluate');
        Route::get('/Evaluate/img/{area}/{Met_evaluates}', 'EvaluateController@detailImg')->name('detail_img_Evaluate');
        Route::post('/post/Evaluate', 'EvaluateController@evaluate')->name('do_Evaluate');
        Route::get('/Evaluate/download/{Time_Period}', 'EvaluateController@download')->name('download_Evaluate');
        Route::delete('/Evaluate/delete/{Met_eva}', 'EvaluateController@delete')->name('delete_Evaluate');


        Route::get('/MetData', 'MetDataController@index')->name('MetData');

        Route::get('/MetMonthData/get/{year}/{month}/{datatype}/{var}', 'MetDataController@MetMonthData')->name('MetMonthData');
        Route::post('/MetMonthData/post/{year}/{month}/{datatype}/{var}', 'MetDataController@MetUpload')->name('UploatMet');
        Route::get('/MetMonthData/download/{DataID}/{datatype}/{var}', 'MetDataController@download')->name('download_MetMonth');
        Route::post('/MetMonthData/multiple/{method}/{datatype}/{var}', 'MetDataController@Multiple')->name('Multiple');


        Route::delete('/DELETE/{DataID}/{datatype}/{var}', 'MetDataController@MetDelete')->name('DeleteMet');
    });
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});
