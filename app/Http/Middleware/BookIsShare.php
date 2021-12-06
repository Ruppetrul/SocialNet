<?php

namespace App\Http\Middleware;

use App\Models\Book;
use Closure;
use Illuminate\Http\Request;

class BookIsShare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $book = Book::find($request->id_book);
        if (isset($book)) {
            if ($book->share) {
                return $next($request);
            }
        }

        abort(403);
    }
}
