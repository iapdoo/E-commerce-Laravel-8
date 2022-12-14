<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\VendorsController;
use Illuminate\Support\Facades\Route;

define('PAGINATION_COUNT', 10);
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
Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/', [DashboardController::class, 'get_dashboard'])->name('admin.dashboard');
    ######################### start language route ##############################
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/', [LanguagesController::class, 'index'])->name('admin.languages');
        Route::get('create', [LanguagesController::class, 'create'])->name('admin.languages.create');
        Route::post('store', [LanguagesController::class, 'store'])->name('admin.languages.store');

        Route::get('edit/{id}', [LanguagesController::class, 'edit'])->name('admin.languages.edit');
        Route::post('update/{id}', [LanguagesController::class, 'update'])->name('admin.languages.update');
        Route::get('delete/{id}', [LanguagesController::class, 'destroy'])->name('admin.languages.delete');
    });
    ######################### End language route ##############################
    ######################### start Main Categories route ##############################
    Route::group(['prefix' => 'main_categories'], function () {
        Route::get('/', [MainCategoryController::class, 'index'])->name('admin.maincategories');
        Route::get('create', [MainCategoryController::class, 'create'])->name('admin.maincategories.create');
        Route::post('store', [MainCategoryController::class, 'store'])->name('admin.maincategories.store');

        Route::get('edit/{id}', [MainCategoryController::class, 'edit'])->name('admin.maincategories.edit');
        Route::post('update/{id}', [MainCategoryController::class, 'update'])->name('admin.maincategories.update');
        Route::get('delete/{id}', [MainCategoryController::class, 'destroy'])->name('admin.maincategories.delete');
    });
    ######################### end Main Categories route ##############################

    ######################### start Vendors route ##############################
    Route::group(['prefix' => 'vendors'], function () {
        Route::get('/', [VendorsController::class, 'index'])->name('admin.vendors');
        Route::get('create', [VendorsController::class, 'create'])->name('admin.vendors.create');
        Route::post('store', [VendorsController::class, 'store'])->name('admin.vendors.store');

        Route::get('edit/{id}', [VendorsController::class, 'edit'])->name('admin.vendors.edit');
        Route::post('update/{id}', [VendorsController::class, 'update'])->name('admin.vendors.update');
        Route::get('delete/{id}', [VendorsController::class, 'destroy'])->name('admin.vendors.delete');
    });
    ######################### end Vendors route ##############################

});
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'get_login'])->name('get.admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
