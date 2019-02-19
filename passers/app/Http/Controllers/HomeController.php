<?php

namespace App\Http\Controllers;

use App\Examinee;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $examinees = Examinee::paginate(50);
        return view('home', compact('examinees'));
    }

    public function getExaminees()
    {
        $examinees = Examinee::paginate(50);
        return response()->json($examinees);
    }
}
