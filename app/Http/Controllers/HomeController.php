<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    }

    public function home() {
        if(Auth::check()) {
            $user = User::where('id',Auth::id())->first();
            return view('home',compact('user'));
        } else {
            return view('home');
        }
    }

    public function load_home_comments(Request $request) {
        if ($request->ajax()) {

            $user = User::where('id',$request->id_user)->first();

            $data = Comment::where('id_user', $request->id_user)
                ->with('reply')
                ->with('author')
                ->skip($request->num)
                ->take(5)
                ->get();

            if(!$data->isEmpty()){
                return view('layouts.comments.table-ajax', [
                      'isHome' => true,
                    'comments' => $data,
                     'id_user' => $request->id_user
                ]);
            }
        }
    }
}
