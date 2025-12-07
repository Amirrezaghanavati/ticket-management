<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FakeApiController;

Route::post('/fake-api', [FakeApiController::class, 'index'])->name('fake-api');
