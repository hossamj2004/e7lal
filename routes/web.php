<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserCarController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCarController;
use App\Http\Controllers\Admin\AdminUserCarController;
use App\Http\Controllers\Admin\AdminOfferController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDatabaseController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/cars', [CarController::class, 'index'])->name('cars');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::get('/how-it-works', function () {
    return view('pages.how-it-works');
})->name('how-it-works');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User cars
    Route::get('/my-cars', [UserCarController::class, 'index'])->name('my-cars.index');
    Route::get('/my-cars/create', [UserCarController::class, 'create'])->name('my-cars.create');
    Route::post('/my-cars', [UserCarController::class, 'store'])->name('my-cars.store');
    Route::get('/my-cars/{userCar}', [UserCarController::class, 'show'])->name('my-cars.show');
    Route::post('/my-cars/{userCar}/set-active', [UserCarController::class, 'setActive'])->name('my-cars.set-active');

    // Offers
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/create/{car}', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/offers/{car}', [OfferController::class, 'store'])->name('offers.store');
    Route::post('/offers/{offer}/cancel', [OfferController::class, 'cancel'])->name('offers.cancel');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Cars management
    Route::resource('cars', AdminCarController::class);

    // User cars management
    Route::get('/user-cars', [AdminUserCarController::class, 'index'])->name('user-cars.index');
    Route::get('/user-cars/pending', [AdminUserCarController::class, 'pending'])->name('user-cars.pending');
    Route::get('/user-cars/{userCar}', [AdminUserCarController::class, 'show'])->name('user-cars.show');
    Route::post('/user-cars/{userCar}/price', [AdminUserCarController::class, 'price'])->name('user-cars.price');
    Route::post('/user-cars/{userCar}/reject', [AdminUserCarController::class, 'reject'])->name('user-cars.reject');

    // Offers management
    Route::get('/offers', [AdminOfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/pending', [AdminOfferController::class, 'pending'])->name('offers.pending');
    Route::get('/offers/{offer}', [AdminOfferController::class, 'show'])->name('offers.show');
    Route::post('/offers/{offer}/accept', [AdminOfferController::class, 'accept'])->name('offers.accept');
    Route::post('/offers/{offer}/reject', [AdminOfferController::class, 'reject'])->name('offers.reject');

    // Users management
    Route::resource('users', AdminUserController::class);
   });
// Database management
Route::get('/admin/database', [AdminDatabaseController::class, 'index'])->name('admin.database.index');
Route::post('/admin/database/run-query', [AdminDatabaseController::class, 'runQuery'])->name('admin.database.run-query');
Route::post('/admin/database/run-command', [AdminDatabaseController::class, 'runCommand'])->name('admin.database.run-command');
Route::get('/admin/database/tables', [AdminDatabaseController::class, 'getTables'])->name('admin.database.tables');
Route::get('/admin/database/table-structure', [AdminDatabaseController::class, 'getTableStructure'])->name('admin.database.table-structure');
