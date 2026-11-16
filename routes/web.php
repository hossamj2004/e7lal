<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserCarController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCarController;
use App\Http\Controllers\Admin\AdminUserCarController;
use App\Http\Controllers\Admin\AdminOfferController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDatabaseController;
use App\Http\Controllers\Admin\AdminExchangeRequestController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\TaskBoardController;
use App\Models\Car;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', function () {
    $latestCars = Car::where('status', 'available')
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();

    return view('welcome', compact('latestCars'));
})->name('home');

Route::get('/cars', [CarController::class, 'index'])->name('cars');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::post('/submit-exchange-request', [CarController::class, 'submitExchangeRequest'])->name('submit-exchange-request');

Route::get('/register-now', function () {
    return view('pages.register-now');
})->name('register-now');

Route::get('/how-it-works', function () {
    return view('pages.how-it-works');
})->name('how-it-works');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Task Board routes (public, no login required for viewing/moving)
Route::get('/tasks', [TaskBoardController::class, 'index'])->name('tasks');
Route::get('/tasks/{token}', [TaskBoardController::class, 'show'])->name('tasks.show');
Route::post('/tasks/{task}/update', [TaskBoardController::class, 'updateTask'])->name('tasks.update');

// Public board operations (CSRF protected, available to everyone on /tasks)
Route::post('/boards/{board}/columns', [TaskBoardController::class, 'addColumn'])->name('boards.add-column');
Route::put('/columns/{column}', [TaskBoardController::class, 'updateColumn'])->name('columns.update');
Route::delete('/columns/{column}', [TaskBoardController::class, 'deleteColumn'])->name('columns.delete');
Route::post('/columns/{column}/tasks', [TaskBoardController::class, 'addTask'])->name('columns.add-task');
Route::delete('/tasks/{task}', [TaskBoardController::class, 'deleteTask'])->name('tasks.delete');

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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
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

    // Exchange requests management
    Route::resource('exchange-requests', AdminExchangeRequestController::class);
    Route::post('/exchange-requests/{exchangeRequest}/toggle-favorite', [AdminExchangeRequestController::class, 'toggleFavorite'])->name('exchange-requests.toggle-favorite');
    Route::get('/exchange-requests-export', [AdminExchangeRequestController::class, 'export'])->name('exchange-requests.export');

    Route::get('/database', [AdminDatabaseController::class, 'index'])->name('database.index');
    Route::post('/database/run-query', [AdminDatabaseController::class, 'runQuery'])->name('database.run-query');
    Route::post('/database/run-command', [AdminDatabaseController::class, 'runCommand'])->name('database.run-command');
    Route::get('/database/tables', [AdminDatabaseController::class, 'getTables'])->name('database.tables');
    Route::get('/database/table-structure', [AdminDatabaseController::class, 'getTableStructure'])->name('database.table-structure');

    // Users management
    Route::resource('users', AdminUserController::class);

    // Tasks and Boards management
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/boards', [AdminTaskController::class, 'boards'])->name('tasks.boards');
    Route::get('/boards/create', [AdminTaskController::class, 'createBoard'])->name('tasks.create-board');
    Route::post('/boards', [AdminTaskController::class, 'storeBoard'])->name('tasks.store-board');
    Route::delete('/boards/{board}', [AdminTaskController::class, 'destroyBoard'])->name('tasks.destroy-board');
   });
// Database management
