<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    private function getContent($user_id) {
        $user = User::where('id',$user_id)->first();
        $comments = Comment::where('id_user',$user_id)->get();
        return view('profile',compact('user','comments'));
    }

    public function profile($profile_id = null) {
        if (isset($profile_id)){
            return $this->getContent($profile_id);
        } else if (Auth::check()) {
            $user_id = Auth::id();
            return $this->getContent($user_id);
            } else {
                return view('welcome');
        }
    }

    public function sendComment(Request $request, $user_id) {
        if (Auth::check()) {
            try {
                $author_id = $request->user()['id'];

                $comment = new Comment;
                $comment->id_comment_author = $author_id;
                $comment->id_user = $user_id;
                $comment->text = $_POST['text'];

                $comment->save();

                return back();
            } catch (\Exception $exception) {
                echo 'Add comment error';
            }

        } else {
            return view('welcome');
        }




        /*DB::table('comments')->insert([
            'id_comment_author' => $author_id,
            'id_user' => $user_id,
            'text' => $_POSt['text'],
        ]);*/
    }
}
