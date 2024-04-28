<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/contact/api', [ContactController::class, 'apiAll'])->name('api_contact');
Route::get('/device/api', [DeviceController::class, 'apiAll'])->name('api_device');
Route::get('/api_voltage', [ReportController::class, 'apiVoltage'])->name('api_voltage');
Route::get('/api_broadcast', [ReportController::class, 'apiBroadcast'])->name('api_broadcast');
Route::get('/user/api', [UserController::class, 'apiAll'])->name('api_user');
Route::get('/get_data_log_sensor', [SensorController::class, 'apiJson'])->name('get_data_log_sensor');
Route::post('/send_data_sensor', [SensorController::class, 'store'])->name('send_data_sensor');
Route::get('/get_donut_chart', [SensorController::class, 'apiDonuts'])->name('get_donut_chart');
