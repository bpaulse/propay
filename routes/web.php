<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceLineController;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () { return view('welcome'); });

Auth::routes();

Route::get('/home',								[HomeController::class, 'index'])->name('home');
Route::get('/person',							[HomeController::class, 'person'])->name('person');
Route::get('/getPeopleList',					[HomeController::class, 'getPeopleList']);
Route::get('/deletePersonLine',					[HomeController::class, 'deletePersonLine']);
Route::get('/getAppSettings',					[HomeController::class, 'getAppSettings']);
Route::get('/buildMultiselect',					[HomeController::class, 'buildMultiselect']);
Route::get('/events',							[HomeController::class, 'events']);

Route::get('/getEventsList',					[EventController::class, 'getEventsList']);
Route::get('/displayEventDetails/{id}', 		[EventController::class, 'displayEventDetails']);
Route::get('/getEventName', 					[EventController::class, 'getEventName']);
Route::get('/getGender', 						[EventController::class, 'getGender']);
Route::get('/getCategory', 						[EventController::class, 'getCategory']);

Route::post('/add-athlete', 					[EventDetailController::class, 'addAthlete'])->name('add.athlete');
Route::post('/add-wod', 						[EventDetailController::class, 'addWod'])->name('add.wod');
Route::post('/edit-wod', 						[EventDetailController::class, 'editWod'])->name('edit.wod');
Route::post('/add-score', 						[EventDetailController::class, 'addScore'])->name('add.score');
Route::post('/add-athlete', 					[EventDetailController::class, 'addAthlete'])->name('add.athlete');
Route::get('/wodDetails/{id}/{wodid}',			[EventDetailController::class, 'wodDetails']);
Route::get('/getAthletesForEvent', 				[EventDetailController::class, 'getAthletesForEvent']);
Route::get('/populateScoringInputForm',			[EventDetailController::class, 'populateScoringInputForm']);
Route::get('/saveInputScoring', 				[EventDetailController::class, 'saveInputScoring']);
Route::get('/saveValueScoring', 				[EventDetailController::class, 'saveValueScoring']);
Route::get('/wodResults/{id}/{wodid}',			[EventDetailController::class, 'wodResults']);
Route::get('/getAllDivisions',					[EventDetailController::class, 'getAllDivisions']);
Route::get('/getOverallStandings',				[EventDetailController::class, 'getOverallStandings']);
Route::get('/getWodsForEvent', 					[EventDetailController::class, 'getWodsForEvent']);

Route::post('/add-client',						[ClientController::class, 'addClient'])->name('add.client');
Route::get('/getClientLineInfo',				[ClientController::class, 'getClientLineInfo']);
Route::get('/client-list',						[ClientController::class, 'index'])->name('client.list');
Route::post('/edit-client',						[ClientController::class, 'editClient'])->name('edit.client');
Route::get('/saveClientToInvoice',				[ClientController::class, 'saveClientToInvoice']);
Route::get('/getClient',						[ClientController::class, 'getClient']);

Route::get('/getInvoiceLineDetails',			[InvoiceLineController::class, 'getInvoiceLineDetails']);
Route::get('/getProductInfo',					[InvoiceLineController::class, 'getProductInfo']);

Route::get('/updateSingleInvoiceField',			[InvoiceController::class, 'updateSingleInvoiceField']);
Route::get('/invoice-list',						[InvoiceController::class, 'index']);
Route::post('/add-invoice',						[InvoiceController::class, 'addInvoice'])->name('add.invoice');
Route::get('/getInvoicesList',					[InvoiceController::class, 'getInvoicesList'])->name('get.invoices.list');
Route::get('/getInvoiceDetails',				[InvoiceController::class, 'getInvoiceDetails']);

Route::get('/getProductServicesList',			[ProductController::class, 'getProductServicesList']);
Route::post('/updateProductLine',				[ProductController::class, 'updateProductLine']);

Route::get('/my-demo-mail',						[InvoiceController::class, 'myDemoMail']);

Route::get('/email-test', function(){
	$details['email'] = 'bevanpaulse@gmail.com';
	$details['to'] = 'bevanpaulse@gmail.com';
	$details['name'] = 'Eli Bailey Paulse';
	$details['subject'] = 'Hello Laravelcode';

	dispatch(new App\Jobs\SendEmailJob($details));
	dd('done');
});
