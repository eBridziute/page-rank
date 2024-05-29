<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageRankController;

Route::get('/page-ranks', [PageRankController::class, 'index'])->name('page-ranks.index');;
