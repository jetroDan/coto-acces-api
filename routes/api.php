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

Route::resource('users','UserController');
Route::get('excelEntries','ExcelController@entries');
Route::resource('cotos','CotoController');
Route::resource('cars','CarBrandController');
Route::resource('invitations','InvitationController');
Route::get('invitations_received','InvitationController@received');
Route::get('entries','InvitationController@entries');
Route::resource('registrations','RegistrationController');
Route::resource('notifications','NotificationController');
Route::get('newTestWeb','NotificationController@newTestWeb');
Route::get('registerToken','NotificationController@registerToken');
Route::post('registrations.status','RegistrationController@status');
Route::post('registrations.verifyqr','RegistrationController@verifyqr');
Route::post('registrations.useqr','RegistrationController@useQr');
Route::post('registrations.registerVisitor','RegistrationController@registerVisitor');
Route::post('registrations.searchHistory','RegistrationController@searchHistory');
Route::post('registrations.searchPreRegistered','RegistrationController@searchPreRegistered');
Route::post('registrations.reactivateQr','RegistrationController@reactivateQr');
Route::post('image','InvitationController@uploadImage');
Route::post('register','InvitationController@register');
Route::post('updateUserInfo','UserController@updateUserInfo');
Route::post('restrictUser','UserController@restrictUser');
Route::get('getCotos','UserController@getCotos');
Route::post('test','InvitationController@test');
Route::get('notifyTest','InvitationController@notifyTest');
Route::get('login','UserController@authenticate');
Route::get('test','UserController@test');
Route::get('getRestricted','UserController@getRestricted');
Route::post('userToken','UserController@userToken');
Route::get('phonetest','UserController@phonetest');
Route::get('phonetest2','UserController@phonetest2');


//Apis de modulo de servicios
Route::post('services','ServiceController@store');
Route::put('services/{service}','ServiceController@update');
Route::get('services','ServiceController@list');

Route::post('providers','ProviderController@store');
Route::put('providers/{provider}','ProviderController@update');
Route::get('providers','ProviderController@list');

Route::post('business','BusinessController@store');
Route::put('business/{business}','BusinessController@update');
Route::get('business','BusinessController@list');

Route::post('employee','EmployeeController@store');
Route::put('employee/{employe}','EmployeeController@update');
Route::get('employee','EmployeeController@list');

