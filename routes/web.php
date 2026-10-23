<?php

use Illuminate\Support\Facades\Route;
use App\Http\Requests\LocaleRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TipController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\LeadController;

Route::post('/locale', function (LocaleRequest $request) {
    $request->session()->put('locale', $request->validated('locale'));

    return redirect()->back();
})->name('locale.update');

Route::middleware('guest')->group(function (): void {
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1')->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('throttle:5,1')->name('password.change.update');
});
Route::middleware('auth')->group(function (): void {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:5,1')->name('leads.store');
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->middleware(['auth', 'active', 'verified'])->name('booking.mine');
Route::post('/my-bookings/{booking}/cancel', [BookingController::class, 'cancelManaged'])
    ->middleware(['auth', 'active', 'verified'])->name('booking.mine.cancel');

Route::get('/', HomeController::class)->name('home');
Route::redirect('/menu', '/menu/our-menu')->name('menu.index');
Route::get('/menu/our-menu', MenuController::class)->name('menu.our-menu');
Route::get('/menu/must-try', [MenuController::class, 'mustTry'])->name('menu.must-try');
Route::get('/menu/combo', [MenuController::class, 'combos'])->name('menu.combos');
Route::get('/menu/promotions', [MenuController::class, 'promotions'])->name('menu.promotions');
Route::get('/menu/for-kid', [MenuController::class, 'kids'])->name('menu.for-kids');
Route::get('/our-secret/recipes', [RecipeController::class, 'index'])->name('secret.recipes');
Route::get('/our-secret/tips', [TipController::class, 'index'])->name('secret.tips');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->middleware('throttle:5,1')->name('booking.store');
Route::get('/booking/availability', [BookingController::class, 'availability'])->name('booking.availability');
Route::post('/booking/cancel', [BookingController::class, 'cancelFromConfirmation'])->name('booking.cancel-confirmation');

Route::middleware(['auth', 'active', 'admin'])->prefix('accounts')->name('admin.accounts.')->group(function (): void {
    Route::middleware('permission:admin.accounts.view')->group(function (): void {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->middleware('permission:admin.accounts.create')->name('create');
        Route::post('/', [AccountController::class, 'store'])->middleware('permission:admin.accounts.create')->name('store');
    });
});

// A logout URL opened from the address bar is a GET request; send it back to
// the panel instead of raising a 405. Actual logout remains Filament's POST.
Route::get('/admin/logout', fn () => redirect('/admin'))
    ->middleware(['web', 'auth'])
    ->name('filament.admin.auth.logout.get');


