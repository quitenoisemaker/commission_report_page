<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RankReportController extends Controller
{
    //
    public function index()
    {
        # code...
        return view('rank.index');
    }
}
