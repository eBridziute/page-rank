<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PageRank;
use Illuminate\Contracts\View\View;

class PageRankController extends Controller
{
    public function index(): View
    {
        $pageRanks = PageRank::orderBy('rank', 'ASC');
        if (request()->has('search')) {
            $pageRanks = $pageRanks->where('domain', 'like', '%' . request('search') . '%');
        }

        return view('page_ranks.index', [
            'pageRanks' => $pageRanks->paginate(100)->appends(['search' => request('search')])
        ]);
    }
}
