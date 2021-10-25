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
Route::post('/login', [
    	'as' => 'login', 'uses' => 'CustomerController@login'
	]);
Route::post('/register', [
    	'as' => 'register', 'uses' => 'CustomerController@register'
	]);
Route::post('/customer/add', [
    	'as' => 'customer.add', 'uses' => 'CustomerController@add'
	]);
Route::group(['middleware' => ['auth:api']], function () {
    
	
	//customers
	Route::get('/customer/list', [
    	'as' => 'customer.list', 'uses' => 'CustomerController@list'
	]);
	
Route::get('/customer/dashboard', [
    	'as' => 'dashbard', 'uses' => 'CustomerController@getSummary'
	]);
	
	Route::get('/customer/payments_by_bill', [
    	'as' => 'payments_bills', 'uses' => 'CustomerController@getPaymentsByBill'
	]);
Route::get('/customer/bills', [
    	'as' => 'bills', 'uses' => 'CustomerController@getBillsByUser'
	]);
	Route::get('/customer/profile', [
    	'as' => 'profile', 'uses' => 'CustomerController@getUserDetails'
	]);
	Route::post('/customer/profile/update', [
    	'as' => 'customer_update', 'uses' => 'CustomerController@update'
	]);

	
});