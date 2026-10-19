<?php

use Illuminate\Support\Facades\Route;
use App\Http\Requests\LocaleRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TipController;
use App\Http\Controllers\AboutController;

Route::post('/locale', function (LocaleRequest $request) {
    $request->session()->put('locale', $request->validated('locale'));

    return redirect()->back();
})->name('locale.update');

Route::get('/', HomeController::class)->name('home');
Route::redirect('/menu', '/menu/our-menu')->name('menu.index');
Route::get('/menu/our-menu', MenuController::class)->name('menu.our-menu');
Route::get('/menu/must-try', [MenuController::class, 'mustTry'])->name('menu.must-try');
Route::get('/menu/combo', [MenuController::class, 'combos'])->name('menu.combos');
Route::get('/menu/promotions', [MenuController::class, 'promotions'])->name('menu.promotions');
Route::get('/menu/for-kid', [MenuController::class, 'forKids'])->name('menu.for-kid');
Route::get('/our-secret/recipes', [RecipeController::class, 'index'])->name('secret.recipes');
Route::get('/our-secret/tips', [TipController::class, 'index'])->name('secret.tips');
Route::get('/about', [AboutController::class, 'index'])->name('about');


