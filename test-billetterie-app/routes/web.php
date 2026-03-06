<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/shows/create', [ShowController::class, 'create'])->name('shows.create');
    Route::post('/shows', [ShowController::class, 'store'])->name('shows.store');
    Route::get('/shows/{show}/edit', [ShowController::class, 'edit'])->name('shows.edit');
    Route::put('/shows/{show}', [ShowController::class, 'update'])->name('shows.update');
    Route::delete('/shows/{show}', [ShowController::class, 'destroy'])->name('shows.destroy');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Payment routes
    Route::get('/payment', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/payment', [CartController::class, 'processPayment'])->name('cart.process-payment');
    Route::get('/payment/confirm', [CartController::class, 'confirmPayment'])->name('cart.payment-confirm');
    Route::post('/payment/success', [CartController::class, 'paymentSuccess'])->name('cart.payment-success');
    Route::get('/payment/success', [CartController::class, 'success'])->name('cart.success');

    // Reservations routes
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.history');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Administration statistics
    Route::get('/admin/stats', [AdminController::class, 'stats'])->name('admin.stats');

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

// Favorites - accessible without auth (redirects to login if not connected)
Route::post('/shows/{show}/favorite', [FavoriteController::class, 'store'])->name('shows.favorite');

Route::get('/', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{show}', [ShowController::class, 'show'])->name('shows.show');
require __DIR__ . '/auth.php';
