<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return 'ok';
// });

Route::group(['middleware' => ['auth']], function () {
   //Route::get('/', 'HomeController@index');
   // return 'ok'
   //    Route::resource('user', 'UserController');
   Route::group(['middleware' => ['auth', 'leveluser:admin']], function () {
      Route::resource('user', UserController::class)->except('show');
      Route::get('/check-username', [UserController::class, 'checkUsername'])->name('checkUsername');

      Route::resource('contact', ContactController::class)->except('show');

      Route::resource('device', DeviceController::class);
   });
   
   Route::get('/realtime-voltage', [SensorController::class, 'index'])->name('realtime_voltage');
   Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

   Route::get('/report/profilspbu', [ReportController::class, 'profilspbu'])->name('report.profilspbu');
   Route::get('/report/voltage', [ReportController::class, 'voltage'])->name('report.voltage');
   Route::get('/report/broadcast', [ReportController::class, 'broadcast'])->name('report.broadcast');
   // routes/web.php

   Route::get('/update-device-status', [SensorController::class, 'updateDeviceStatus'])->name('update_device_status');
});

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
