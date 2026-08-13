<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/album', function () {
    // Redirect to the static album index placed under public/album
    return redirect('/album/index.html');
})->name('album');
