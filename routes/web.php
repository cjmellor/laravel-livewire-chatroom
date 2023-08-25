<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\Message;
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

Route::get('/', fn () => to_route('chat'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('chat', fn () => view('chat'))
        ->name('chat');

    Route::get('livewire-chat', fn () => Blade::render('<livewire:chat />'))
        ->name('livewire-chat');

    Route::get('users', fn () => Blade::render('<livewire:users />'))
        ->name('users');
});

Route::middleware('auth')->group(function () {
    Route::post('messages', MessageController::class)
        ->name('messages.store');

    Route::get('fetch-messages', function () {
        return Message::with('user:id,name')
            ->get(['id', 'message', 'user_id']);
    })
        ->name('fetch-messages');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
