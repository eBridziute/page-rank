<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;
use App\Jobs\FetchAndUpdatePageRanks;

Schedule::job(new FetchAndUpdatePageRanks)->daily();
