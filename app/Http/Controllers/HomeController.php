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

            $data = DB::table('comments as profile_comments')
                ->leftJoin('users as profile_users','profile_users.id','=',
                    'profile_comments.id_comment_author')
                ->where('profile_comments.id_comment_author','=', $request->id_user)

                ->leftJoin('comments as reply_comments','profile_comments.id_comment_reply',
                    '=','reply_comments.id_comment')
                ->leftJoin('users as reply_users','reply_users.id','=',
                    'reply_comments.id_comment_author')
                ->select('profile_comments.id_comment',
                    'profile_comments.text',
                    'profile_comments.title',
                    'profile_comments.created_at',
                    'profile_comments.id_comment_author',
                    'profile_comments.id_comment_reply as id_comment_reply',
                    'profile_users.name',

                    'reply_comments.text as reply_text',
                    'reply_comments.title as reply_title',
                    'reply_comments.created_at as reply_created_at',
                    'reply_comments.id_comment_author as reply_id_comment_author',
                    'reply_users.name as reply_author_name',
                )
                ->skip($request->num)
                ->take(5)
                ->get();

            if(!$data->isEmpty()){
                return view('layouts.comments.table-ajax', [
                    'comments' => $data,
                ]);
            }
        }
    }
}
