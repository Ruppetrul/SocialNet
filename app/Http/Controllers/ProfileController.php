<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller {

    private function getContent($request, $commented_comment = null) {

        $data = array();

        if(isset($request->user)) {
            $data['user'] = $request->user;
            $data['access_to_user'] = $request->access_to_user;
            $data['access_to_me'] = $request->access_to_me;
        } else {
            $user = User::find(Auth::id());
            $data['user'] = $user;
        }

        if (isset($commented_comment)) {
            $data['commented_comment'] = $commented_comment;
        }

        $data = (object)$data;
        //dd($data);
        return view('profile', compact('data'));
    }

    public function profile(Request $request, User $user) {

        // for test
        // $this->load_data($request);

        $request->user = $user;

        $commented_comment = Comment::where('id_comment',$request->reply)
        ->with('author')
        ->first();

        return $this->getContent($request,$commented_comment);

    }

    public function sendComment(Request $request, $id_user, $id_reply = null) {
          try {
                $comment = new Comment;
                $comment->id_comment_author = Auth::id();
                $comment->id_user = $id_user;
                $comment->text = $request->text;
                $comment->title = $request->title;
                $comment->id_comment_reply = $id_reply;
                $comment->save();
                return redirect('profile/'.$id_user);

          } catch (\Exception $exception) {
              echo 'Add comment error';
          }
    }

    public function deleteComment(Request $request, $id_comment) {
          $id_remover = $request->user()->id;
          $comment = Comment::where('id_comment',$id_comment)->first();
          if (isset($comment)) {
                $id_comment_author = $comment->id_comment_author;
                if ($id_remover == $id_comment_author || $id_remover == $comment->id_user) {
                      $deleteComment = Comment::where('id_comment', $id_comment)->delete();
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

                $data = Comment::where('id_user', $request->id_user)
                    ->with('reply')
                    ->with('author')
                    ->skip($request->num)
                    ->take(5)
                    ->get();

            if(!$data->isEmpty()){
                return view('layouts.comments.table-ajax', [
                    'comments' => $data,
                     'id_user' => $request->id_user,
                ]);
            }
        }
    }
}
