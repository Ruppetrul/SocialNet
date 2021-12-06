<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use League\CommonMark\Node\Block\Document;

class LibraryController extends Controller
{
    public function library(Request $request, $id_user = null ) {
        if (isset($id_user)) {
            $req = Access::where('id_reader', Auth::id())
                ->where('id_author', $id_user);
            $access = $req->first();
            if (isset($access) || $id_user == Auth::id()) {

                $user = User::find($id_user);

                return view('library' , [
                    'user' => $user
                ]);
            }
            else {
                echo 'This user has not given you access to their library';
            }
        } else {
            $user = User::find(Auth::id());

            return view('library' , [
                'user' => $user
            ]);
        }
    }

    public function share_book(Request $request) {
        $book = Book::find($request->book_id);
        $book->share = !$book->share;

        if ($book->share) {
            $url = URL::signedRoute('read_share_book', ['id_book' => $request->book_id]);
            $book->link = $url;
        } else {
            $book->link = null;
        }

        $book->save();
        return redirect('library/'.$book->id_author);
    }

    public function read_book(Request $request, $id_book) {
        $book = Book::find($id_book);
        $req = Access::where('id_reader', Auth::id())
            ->where('id_author', $book->id_author);
        $access = $req->first();

        if(isset($access) || $book->id_author == Auth::id()) {
            if (isset($book)) {
                return view('book-edit', [
                    'book' => $book,
                    'isRead' => true
                ]);
            }
        } else {
            echo 'нет доступа к книге';
        }
    }

    public function read_share_book(Request $request, $id_book) {
        $book = Book::find($id_book);

        return view('book-edit', [
            'book' => $book,
            'isRead' => true
        ]);
    }

    public function add_book(Request $request) {

        $book = new Book;
        $book->id_author = Auth::id();
        $book->name = $request->name;
        $book->text = $request->text;
        $result = $book->save();

        if ($result) {
            return redirect('library');
        } else {
            echo 'Error add book';
        }
    }

    public function load_data(Request $request) {
        $data = DB::table('books')
            ->where('id_author','=', $request->id_user)
            ->skip($request->num)
            ->take(5)
            ->get();

        return view('layouts.library.book-ajax', [
            'books' => $data,
        ]);
    }

    public function allow_access(Request $request) {

        $access = new Access;
        $access->id_author = Auth::id();
        $access->id_reader = $request->id_user;
        $access->save();

        return redirect('profile/'.$request->id_user);
    }

    public function limit_access(Request $request){
        $req = Access::where('id_reader', $request->id_user)
            ->where('id_author', Auth::id());
        $access = $req->first();

        if (isset($access)) {
            $req->delete();
        }

        return redirect('profile/'.$request->id_user);
    }

    public function delete_book(Request $request) {
        $req = Book::where('id', $request->book_id);
        $book = $req->first();

        if (isset($book)) {
            $deleteBook = Book::where('id', $request->book_id)->delete();
        } else {
            echo 'Book not found';
        }

        return back();
    }

    public function edit_book(Request $request, $book_id = null) {
        if (isset($book_id)) {

            $req = Book::where('id', $request->book_id);
            $book = $req->first();

            if (isset($book)) {
                return view('book-edit', [
                    'book' => $book,
                ]);
            } else {
                echo 'Book not found';
            }
        } else {
            return view('book-edit');
        }
    }

    public function alter_book(Request $request, $id_book) {

        $book = Book::find($id_book);
        $book->name = $request->name;
        $book->text = $request->text;
        $result = $book->save();

        if ($result) {
            return redirect('library/'.$book->id_author);
        }
    }
}

