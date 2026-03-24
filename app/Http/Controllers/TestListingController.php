<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestListingController extends Controller
{
    public function __invoke()
    {
        $games = \App\Models\Game::where('is_active', true)->get();
        return view('listings.create', compact('games'));
    }
}
