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
Route::get('/cities/list', [
    	'as' => 'cities.list', 'uses' => 'CitiesController@getActiveCities'
	]);
Route::get('/delivery_days/list', [
    	'as' => 'delivery_days.list', 'uses' => 'DeliveryDaysController@list'
	]);
Route::get('/areas/list', [
    	'as' => 'areas.list', 'uses' => 'AreasController@getActiveAreas'
	]);


		Route::get('/customer/list', [
    	'as' => 'customer.list', 'uses' => 'CustomerController@list'
	]);


	//disable auth
Route::group(['middleware' => ['auth:api']], function () {
    
	
	//customers


	Route::get('/customer/search', [
    	'as' => 'customer.search', 'uses' => 'CustomerController@search'
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

Route::get('/customer/view', [
    	'as' => 'view', 'uses' => 'CustomerController@getUserDetailsById'
	]);

	Route::post('/customer/profile/update', [
    	'as' => 'customer_update', 'uses' => 'CustomerController@update'
	]);
Route::post('/customer/update', [
    	'as' => 'update', 'uses' => 'CustomerController@updateCustomer'
	]);
	Route::get('/brand/list', [
    	'as' => 'brand.list', 'uses' => 'BrandController@list'
	]);

	Route::post('/brand/add', [
    	'as' => 'brand.add', 'uses' => 'BrandController@add'
	]);
Route::post('/brand/update', [
    	'as' => 'brand.update', 'uses' => 'BrandController@update'
	]);
	Route::get('/brand/get_by_id', [
    	'as' => 'brand.get_by_id', 'uses' => 'BrandController@getByBrandId'
	]);
	Route::get('/routes/get_by_id', [
    	'as' => 'routes.get_by_id', 'uses' => 'RoutesController@getByRouteId'
	]);
	Route::get('/brand/get_active_brands', [
    	'as' => 'brand.get_active_brands', 'uses' => 'BrandController@getActiveBrands'
	]);
		Route::get('/customer/searchable', [
    	'as' => 'customer.searchable', 'uses' => 'CustomerController@searchableCustomers'
	]);
Route::get('/customer/search_for_combo', [
    	'as' => 'customer.search_for_combo', 'uses' => 'CustomerController@searchForCombo'
	]);
	Route::get('/remarks/get_remarks', [
    	'as' => 'remarks.get_remarks', 'uses' => 'RemarksController@getActiveRemarks'
	]);

	Route::get('/supply/search/records', [
    	'as' => 'profile', 'uses' => 'SupplyController@getSupplyRecords'
	]);
Route::get('/supply/getSupplyRecordsForBill', [
    	'as' => 'profile', 'uses' => 'BillsController@getSupplyRecordsForBill'
	]);
Route::get('/supply/getSupplyRecordsSummaryByBillId', [
    	'as' => 'profile', 'uses' => 'BillsController@getSupplyRecordsSummaryByBillId'
	]);
Route::get('/supply/getSupplyRecordsSummary', [
    	'as' => 'profile', 'uses' => 'BillsController@getSupplyRecordsSummary'
	]);
Route::get('/bills/SearchBills', [
    	'as' => 'profile', 'uses' => 'BillsController@SearchBills'
	]);
Route::get('/bills/getBillingStatusList', [
    	'as' => 'profile', 'uses' => 'BillsController@getBillingStatusList'
	]);
Route::post('/bills/add', [
    	'as' => 'bills.add', 'uses' => 'BillsController@add'
	]);
	Route::post('/supply/add', [
    	'as' => 'supply.add', 'uses' => 'SupplyController@add'
	]);

	Route::get('/bills/view', [
    	'as' => 'bills.view', 'uses' => 'BillsController@view'
	]);

	Route::get('/routes/list', [
    	'as' => 'routes.list', 'uses' => 'RoutesController@list'
	]);
	Route::post('/routes/add', [
    	'as' => 'routes.add', 'uses' => 'RoutesController@add'
	]);
	Route::post('/routes/update', [
    	'as' => 'routes.update', 'uses' => 'RoutesController@update'
	]);

	Route::get('/routes/for_combo', [
    	'as' => 'routes.for_combo', 'uses' => 'RoutesController@getActiveRoutes'
	]);
});