<?php

use App\Http\Controllers\LinkedListController;
use App\Http\Controllers\LinkedListItemController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::controller(LinkedListItemController::class)->group(function () {
    Route::get('/lists/{linkedList}/items', 'index')->name('lists.items.index');
    Route::post('/lists/{linkedList}/items', 'store')->name('lists.items.store');
    Route::get('/lists/{linkedList}/items/create', 'create')->name('lists.items.create');
    Route::get('/lists/{linkedList}/items/{item}/edit', 'edit')->name('lists.items.edit');
    Route::patch('/lists/{linkedList}/items/{item}', 'update')->name('lists.items.update');
    Route::delete('/lists/{linkedList}/items/{item}', 'destroy')->name('lists.items.destroy');
});

Route::controller(LinkedListController::class)->group(function () {
    Route::get('/lists', 'index')->name('lists.index');
    Route::post('/lists', 'store')->name('lists.store');
    Route::get('/lists/create', 'create')->name('lists.create');
    Route::get('/lists/{linkedList}/edit', 'edit')->name('lists.edit');
    Route::patch('/lists/{linkedList}', 'update')->name('lists.update');
    Route::delete('/lists/{linkedList}', 'destroy')->name('lists.destroy');
});
