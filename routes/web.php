<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\EventTypesController;
use App\Http\Controllers\VenuesManagementController;
use App\Http\Controllers\AreasManagementController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\staffController;
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

Route::middleware(['auth', 'verified'])->group(function () {

Route::get('setlocale/{locale}', 'App\Http\Controllers\LocalizationController@setLocale')->name('setlocale');

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    // Routes for Venues Management
    Route::resource('/venues-management/venues', VenuesManagementController::class)->names([
        'index' => 'venues', // Rename the index route
    ]);

    // Routes for Areas Management
    Route::resource('/venues-management/areas', AreasManagementController::class)->names([
        'index' => 'areas', // Rename the index route
    ]);

    Route::resource('/contacts', ContactsController::class)->names([
        'index' => 'contacts'
    ]);

    Route::resource('/bookings', BookingsController::class)->names([
        'index' => 'bookings'
    ]);

    Route::resource('/staff', staffController::class)->names([
        'index' => 'staff'
    ]);

    Route::resource('/prices', PricesController::class)->names([
        'index' => 'prices'
    ]);

    Route::resource('/seasons', SeasonsController::class)->names([
        'index' => 'seasons'
    ]);

    Route::resource('/options', OptionsController::class)->names([
        'index' => 'options'
    ]);

    Route::resource('/event-types', EventTypesController::class)->names([
        'index' => 'event-types'
    ]);

    Route::resource('/organizations', TenantController::class)->names([
        'index' => 'organizations'
    ]);

    Route::resource('/quotes', QuotesController::class)->names([
        'index' => 'quotes'
    ]);

    Route::get('quotes/{quote}', [QuotesController::class, 'show'])->name('quotes.show');
    Route::post('/quotes/{id}/book', [QuotesController::class, 'book'])->name('quotes.book');

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

Route::get('quotesLink/{hashedId}', [QuotesController::class, 'showPublic'])->name('quotes.showPublic')->middleware('web');

Route::post('/set-tenant', 'App\Http\Controllers\TenantController@setTenant')->name('set-tenant');

require __DIR__ . '/auth.php';
