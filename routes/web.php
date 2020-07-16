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

Route::get('/', function () {
  return view('welcome');
});


Route::group(['prefix' => 'contestant'], function () {
  Route::post('login', 'Auth\LoginController@contestant_login')->name('process_login');
  Route::post('register', 'Auth\RegisterController@contestant_register')->name('process_register');
  Route::post('logout', 'Auth\LoginController@logout')->name('logout');
  Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
  Route::get('login', 'Auth\LoginController@show_contestant_login_form')->name('login');
  Route::get('register', 'Auth\RegisterController@show_contestant_register_form')->name('register');
  Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
  Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
  Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
  Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
  Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
  Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

  Route::group(['middleware' => ['auth'], 'prefix' => 'profile'], function () {
    Route::get('/edit', 'ContestantController@edit')->name('contestant_edit_profile');
    Route::post('/update/{contestant_id}', 'ContestantController@update')->name('contestant_update_profile');
  });
  Route::post('/find/{param}', 'ContestantController@find')->name('find_contestant');
  Route::get('/vote/list', 'VoteController@index')->name('contestant_votes');
  Route::group(['prefix' => 'contest'], function () {
    Route::post('list/', 'ContestController@index')->name('list_contest');
    Route::get('{contest_id}/vote/list', 'VoteController@index')->name('contestant_contest_votes');
    Route::post('{contest_id}/find/{param}', 'ContestantController@find')->name('find_contest_contestant');
    Route::get('{contest_id}/contestant/list', 'ContestantController@contestant')->name('list_contest_contestant');
    Route::group(['middleware' => ['auth']], function () {
      Route::get('/{contest_id}/join', 'ContestantController@join')->name('join_contest');
      Route::post('/{contest_id}/vote/{contestant_id}/', 'VoteController@store')->name('vote_contestant');
    });
  });
});


Route::group(['prefix' => 'admin'], function () {
  Route::post('login', 'Auth\LoginController@admin_login')->name('process_admin_login');
  Route::post('register', 'Auth\RegisterController@admin_register')->name('process_admin_register');
  Route::post('password/confirm', 'Auth\ConfirmPasswordController@admin_confirm');
  Route::post('logout', 'Auth\LoginController@admin_logout')->name('admin_logout');
  Route::get('login', 'Auth\LoginController@show_admin_login_form')->name('admin_login');
  Route::get('register', 'Auth\RegisterController@show_admin_register_form')->name('admin_register');
  Route::get('email/verify', 'Auth\VerificationController@admin_show')->name('admin_verification.notice');
  Route::post('password/reset', 'Auth\ResetPasswordController@admin_reset')->name('admin_password.update');
  Route::post('email/resend', 'Auth\VerificationController@admin_resend')->name('admin_verification.resend');
  Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@admin_verify')->name('admin_verification.verify');
  Route::get('password/confirm', 'Auth\ConfirmPasswordController@admin_showConfirmForm')->name('admin_password.confirm');
  Route::get('password/reset', 'Auth\ForgotPasswordController@admin_showLinkRequestForm')->name('admin_password.request');
  Route::post('password/email', 'Auth\ForgotPasswordController@admin_sendResetLinkEmail')->name('admin_password.email');
  Route::get('password/reset/{token}', 'Auth\ResetPasswordController@admin_showResetForm')->name('admin_password.reset');

  Route::group(['middleware' => ['auth:admin']], function () {
    Route::group(['prefix' => 'profile',], function () {
      Route::get('/edit/{contestant_id}', 'ContestantController@edit')->name('admin_edit_profile');
      Route::post('/update/{contestant_id}', 'ContestantController@update')->name('admin_update_profile');
    });

    Route::group(['prefix' => 'contestant',], function () {
      Route::get('/list', 'ContestantController@edit')->name('admin_edit_contestant');
      Route::post('/find/{name}', 'ContestantController@find')->name('admin_find_contestant');
    });


    Route::group(['prefix' => 'contest',], function () {
      Route::post('/list', 'ContestController@index')->name('admin_list_contest');
      Route::get('/create', 'ContestController@create')->name('admin_create_contest');
      Route::post('/save', 'ContestController@create')->name('admin_save_contest');
      Route::get('/edit', 'ContestController@create')->name('admin_edit_contest');
      Route::post('/update', 'ContestController@create')->name('admin_update_contest');
    });
  });
});



Route::get('/home', 'HomeController@index')->name('home');
Route::get('/contestant/all/', 'ContestantController@index')->name('contestant_list');
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
