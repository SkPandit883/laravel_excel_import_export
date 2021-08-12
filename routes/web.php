<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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
Route::get('manage-employee',[EmployeeController::class,'manageEmployee']);
Route::get('file-import-export', [EmployeeController::class, 'fileImportExport']);
Route::post('file-import', [EmployeeController::class, 'fileImport'])->name('file-import');
Route::get('file-export', [EmployeeController::class, 'fileExport'])->name('file-export');
Route::resource('Employee', EmployeeController::class);