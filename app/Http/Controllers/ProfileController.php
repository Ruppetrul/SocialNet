<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ProfileController extends Controller
{
    private function getContent($user_id, $reply = null ) {
        $user_id = htmlspecialchars($user_id);
        //$reply = htmlspecialchars($reply);

        $user = User::where('id',$user_id)->first();

        $comments = DB::table('comments as profile_comments')
            ->leftJoin('users as profile_users','profile_users.id','=',
                'profile_comments.id_comment_author')
            ->where('profile_comments.id_user','=',$user_id)

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

            ->orderBy('profile_comments.created_at','desc')
            ->paginate(5);

        if(isset($user)) {
            return view('profile',compact('user','comments','reply'));
        } else {
            echo 'User nor found';
        }

    }

    public function profile($profile_id = null) {
        $profile_id = htmlspecialchars($profile_id);

        if (isset($profile_id) && is_numeric($profile_id)){

            if (isset($_GET['reply'])) {
                $reply = DB::table('comments')
                    ->where('id_comment', htmlspecialchars($_GET['reply']))
                    ->leftJoin('users', 'comments.id_comment_author','=','users.id')
                    ->select('users.id as id_author',
                        'users.name as author_name',
                        'comments.id_comment',
                        'comments.text',
                        'comments.title')
                    ->first();
                return $this->getContent($profile_id,$reply);
            }

            return $this->getContent($profile_id);
        } else if (Auth::check()) {
            $user_id = Auth::id();
            return redirect('profile/'.$user_id);
        } else {
            return redirect('home');
        }
    }

    public function sendComment(Request $request, $user_id, $reply_id = null) {
        $user_id = htmlspecialchars($user_id);
        $reply_id = htmlspecialchars($reply_id);
        if (Auth::check()) {
                if (isset($user_id) && is_numeric($user_id)) {
                    try {
                    $author_id = $request->user()['id'];
                    $comment = new Comment;
                    $comment->id_comment_author = $author_id;
                    $comment->id_user = $user_id;
                    $comment->text = htmlspecialchars($_POST['text']);
                    $comment->title = htmlspecialchars($_POST['title']);
                    if (is_numeric($reply_id)) {
                        $comment->id_comment_reply = $reply_id;
                    }
                    $comment->save();
                    $_GET['reply'] = null;
                    return redirect('profile/'.$user_id);

                } catch (\Exception $exception) {
                    echo 'Add comment error';
                }
                } else {
                    return redirect('profile/'.$user_id);
                }
        } else {
            return redirect('home');
        }
    }

    public function deleteComment(Request $request, $comment_id) {

        //$comment_id = htmlspecialchars($comment_id);

        if (Auth::check() && is_numeric($comment_id)) {
            $remover_id = $request->user()['id'];
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
        } else {
            return view('home');
        }
    }

    public function load_data(Request $request) {
        if ($request->ajax()) {
            if ($request->id > 0) {
                $data = DB::table('comments as profile_comments')
                    ->leftJoin('users as profile_users','profile_users.id','=',
                        'profile_comments.id_comment_author')
                    ->where('profile_comments.id_user','=',1)

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
                    ->orderBy('profile_comments.created_at','desc')
                    ->where('id','<', $request->id)
                    ->limit(5)
                    ->get();
            } else {
                $data = DB::table('comments as profile_comments')
                    ->leftJoin('users as profile_users','profile_users.id','=',
                        'profile_comments.id_comment_author')
                    ->where('profile_comments.id_user','=',1)

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
                    ->orderBy('profile_comments.created_at','desc')
                    ->limit(5)
                    ->get();
            }

            $output = '';
            $last_id = '';

            if(!$data->isEmpty()){
                foreach ($data as $row) {
                    $output .= '<div class="row">
                        <div class="col-md-12">
                            <h3 class="text-info"><b>'.$row->post.'
                            </b></h3>
                            <p>'.$row->post_secr.'</p>
                            <br/>
                            <div class="col-md-6">
                                <p><b>Publish Date - '.'</b></p>
                            </div>
                            <div class="col-md-6" align="right">
                            <p><b><i>By - </i></b></p>
                            </div>
                            <br />
                            <hr />
                        </div>';
                    $last_id = $row->id;
                }
                $output .= '
                <div id="load_more">


                    <button type="button" name="load_more_button"
                    class="btn btn-info form-control" data-id="'.$last_id.'" id="load_more_button">

                    </button>

                </div>';
            } else {
                $output .= '<div id="load_more">
                    <button type="button" name="load_more_button" class="btn btn-info form-control">
                    No Data Found
                    </button> </div>';
            }
            echo $output;

            /*$page=$_POST['page']; // в моём случае пост запросом передается массив чисел вида [1,2,3,4...], здесь я этот массив принимаю.
            //return View::make('home.more')->with('more', Model::whereNotIn('id','!=', $ids))->get(); //делаем запрос в базу данных, получаем статьи в которых нет id из массива $ids
            return Comment::table('comments as profile_comments')
                ->leftJoin('users as profile_users','profile_users.id','=',
                    'profile_comments.id_comment_author')
                ->where('profile_comments.id_user','=',1)

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

                ->orderBy('profile_comments.created_at','desc')
                ->limit(5)->offset(1)
                ->get();*/
        }
    }
}
