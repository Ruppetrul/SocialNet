<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
    private function getContent($user_id) {
        $user = User::where('id',$user_id)->first();
        //$comments = Comment::where('id_user',$user_id)->orderby('created_at','desc')->paginate(5);

        $replys = DB::table('comments')
            ->join('users','users.id','=','comments.id_comment_author')
            ->select( 'comments.id_comment_reply','comments.text as reply_text','users.name as reply_author_name')
            ->where('comments.id_comment_reply','!=',null)
            ->where('comments.id_user','=',$user_id);

        $comments = DB::table('comments')
            ->join('users','users.id', "=", 'comments.id_comment_author')
            ->where('comments.id_user','=',$user_id)
            ->orderBy('comments.created_at','desc')
            ->leftJoinSub($replys,'replys', function ($join) {
                $join->on('comments.id_comment_reply', '=', 'replys.id_comment_reply');
            })
            ->get();

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

    public function sendComment(Request $request, $user_id, $reply_id = null) {
        if (Auth::check()) {
            try {
                $author_id = $request->user()['id'];
                $comment = new Comment;
                $comment->id_comment_author = $author_id;
                $comment->id_user = $user_id;
                $comment->text = $_POST['text'];
                if (isset($reply_id)) {
                    $comment->id_comment_reply = $reply_id;
                }
                $comment->save();
                $_GET['reply'] = null;
                return redirect('profile/'.$user_id);

            } catch (\Exception $exception) {
                echo 'Add comment error';
            }
        } else {
            return view('welcome');
        }
    }

    public function deleteComment(Request $request, $comment_id) {
        if (Auth::check()) {
            $remover_id = $request->user()['id'];
            $comment = Comment::where('id',$comment_id)->first();
            if (isset($comment)) {
                $comment_author_id = $comment->id_comment_author;
                if ($remover_id == $comment_author_id || $remover_id == $comment->id_user) {
                    $deleteComment = Comment::where('id', $comment_id)->delete();
                    if($deleteComment) {
                        return back();
                    } else {
                        echo 'Error delete comment';
                    }
                }
                else echo 'You do not have sufficient authority to delete this post';

            } else {
                echo 'Comment not found';
            }
        } else {
            return view('welcome');
        }
    }
}
