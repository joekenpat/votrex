<?php

use Illuminate\Support\Facades\Auth;
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
/* guest routes */
Route::get('/home', function () {
  if (Auth::check() && Auth::user()->is_admin()) {
    return redirect()->route('contestant_view_profile');
  }
  return redirect()->route('list_contest');
});

/* Authentication Routes */

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');



Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {
  Route::get('/edit', 'ContestantController@edit')->name('contestant_edit_profile');
  Route::post('/update', 'ContestantController@update')->name('contestant_update_profile');
  Route::get('/home', 'ContestantController@view_profile')->name('contestant_view_profile');
  Route::get('/votes/', 'VoteController@index')->name('contestant_votes');
});
/* user route for contest manipulation */
Route::group(['prefix' => 'contest'], function () {
  Route::get('/', 'ContestController@index')->name('list_contest');
  Route::post('{contest_id}/find', 'ContestController@find_contest_contestant')->name('find_contestant');
  Route::get('/{contest_id}/contestant/', 'ContestController@list_contest_contestant')->name('list_contest_contestant');
  Route::post('/{contest_id}/vote/{contestant_id}/', 'VoteController@store')->name('vote_contestant');
  Route::get('/{contest_id}/visit/{contestant_id}/', 'ContestController@visit_contest_contestant')->name('visit_contest_contestant');

  /* authenticated user route for contest manipulation */
  Route::group(['middleware' => ['auth']], function () {
    Route::get('/{contest_id}/join', 'ContestController@join')->name('join_contest');
    Route::get('/application/all', 'ContestController@get_application')->name('get_application');
  });
});

/* admin route for user manipulation */
Route::group(['prefix' => 'contestant', 'middleware' => ['auth', 'is_admin']], function () {
  Route::get('/list', 'ContestantController@index')->name('admin_list_contestant');
  Route::post('/find/{name}', 'ContestantController@find')->name('admin_find_contestant');
});


/* admin route for contest manipulation */
Route::group(['prefix' => 'contest', 'middleware' => ['auth', 'is_admin']], function () {
  Route::get('/list', 'ContestController@index')->name('admin_list_contest');
  Route::get('/create', 'ContestController@create')->name('admin_create_contest');
  Route::post('/save', 'ContestController@store')->name('admin_save_contest');
  Route::get('/edit/{contest_id}', 'ContestController@edit')->name('admin_edit_contest');
  Route::post('/update/{contest_id}', 'ContestController@update')->name('admin_update_contest');
  Route::get('/delete/{contest_id}', 'ContestController@destroy')->name('admin_delete_contest');
  Route::get('/application/{status}', 'ContestController@admin_get_application')->name('admin_get_application');
  Route::get('/{contest_id}/set_application/{contestant_id}/status/{status}', 'ContestController@set_application')->name('admin_set_application');

});

/* admin route for school manipulation */
Route::group(['prefix' => 'school', 'middleware' => ['auth', 'is_admin']], function () {
  Route::get('/list', 'SchoolController@index')->name('admin_list_school');
  Route::get('/create', 'SchoolController@create')->name('admin_create_school');
  Route::post('/save', 'SchoolController@store')->name('admin_save_school');
  Route::get('/edit/{school_id}', 'SchoolController@edit')->name('admin_edit_school');
  Route::post('/update/{school_id}', 'SchoolController@update')->name('admin_update_school');
  Route::get('/delete/{school_id}', 'SchoolController@destroy')->name('admin_delete_school');
});


/* paystack routes */
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
Route::get('/payment/callback', 'PaymentController@handleGatewayCallback')->name('payment_callback');


Route::get('vote/list', 'VoteController@index')->name('list_vote')->middleware('auth');
Route::get('/contestant/{contestant_id}', 'ContestantController@visit_contestant')->name('visit_contestant');
