<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            return view('profile');
        } else {
            return view('search');
        }
    }

    public function search(Request $request) {
        $name = $request->name;
        if (isset($name)) {
            $users = User::where('name', 'LIKE', "%{$name}%")
                ->orderBy('name')->paginate(10);
            return view('search', compact('users'));
        } else {
            $users = User::orderBy('name')->paginate(10);
            return view('search', compact('users'));
        }
    }
}
