<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function profile($profile_id = null) {
        if (isset($profile_id)){
            $user = User::where('id',$profile_id)->first();
            $comments = Comment::where('id_user',$profile_id)->get();
            return view('profile',compact('user','comments'));
        } else {
            if (Auth::check()) {
            $user = Auth::user();
            $comments = Comment::where('id_user',Auth::id())->get();
            return view('profile',compact('user','comments'));
            } else {
                return view('welcome');
            }
        }
    }
}
