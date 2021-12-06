<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ProfileController extends Controller {
    private function getContent($user_id, $reply = null ) {
        if (isset($user_id)) {
            $user = User::where('id',$user_id)->first();
            $access_to_user = DB::table('accesses')
                ->where('id_reader', $user_id)
                ->where('id_author', Auth::id())
                ->select('*')
                ->first();

            $access_to_me = DB::table('accesses')
                ->where('id_reader', Auth::id())
                ->where('id_author', $user_id)
                ->select('*')
                ->first();
            //dd($access_to_me);

            if(isset($access_to_me)) {
                $books = DB::table('books')
                    ->where('id_author', $user_id)
                    ->select('*')
                    ->get();
                return view('profile',compact('user','reply','access_to_user','books'));
            }

            if (isset($user))
                return view('profile',compact('user','reply','access_to_user'));
            else
                return 'user not found';
        } else {
            $user = User::where('id', Auth::id())->first();
            return view('profile',compact('user','reply'));
        }
    }

    public function profile(Request $request, $profile_id = null) {
        if (isset($request->reply)) {
            $reply = DB::table('comments')
                ->where('id_comment', $request->reply)
                ->leftJoin('users', 'comments.id_comment_author','=','users.id')
                ->select('users.id as id_author',
                    'users.name as author_name',
                    'comments.id_comment',
                    'comments.text',
                    'comments.title')
                ->first();
            return $this->getContent($profile_id,$reply);
        }
        else {
            return $this->getContent($profile_id);
        }
    }

    public function sendComment(Request $request, $profile_id, $reply_id = null) {
        //dd($request);
          try {
                $comment = new Comment;
                $comment->id_comment_author = Auth::id();
                $comment->id_user = $profile_id;
                $comment->text = $request->text;
                $comment->title = $request->title;
                $comment->id_comment_reply = $reply_id;
                $comment->save();
                return redirect('profile/'.$profile_id);

          } catch (\Exception $exception) {
              echo 'Add comment error';
          }
    }

    public function deleteComment(Request $request, $comment_id) {
          $remover_id = $request->user()->id;
          $comment = Comment::where('id_comment',$comment_id)->first();
          if (isset($comment)) {
                $comment_author_id = $comment->id_comment_author;
                if ($remover_id == $comment_author_id || $remover_id == $comment->id_user) {
                      $deleteComment = Comment::where('id_comment', $comment_id)->delete();
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
    }

    public function load_data(Request $request) {
        if ($request->ajax()) {
            $user = User::where('id',$request->id_user)->first();

                $data = DB::table('comments as profile_comments')
                    ->leftJoin('users as profile_users','profile_users.id','=',
                        'profile_comments.id_comment_author')
                    ->where('profile_comments.id_user','=',$request->id_user)
                    ->leftJoin('comments as reply_comments','profile_comments.id_comment_reply',
                        '=','reply_comments.id_comment')
                    ->leftJoin('users as reply_users','reply_users.id','=',
                        'reply_comments.id_comment_author')
                    ->select(
                        'profile_comments.id_comment',
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
                        'user' => $user,
                ]);
            }
        }
    }
}
