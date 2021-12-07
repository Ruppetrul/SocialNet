<?php

namespace App\Http\Middleware;

use App\Models\Access;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LibraryAccess
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
        $id_user = $request->id_user;

        if (isset($id_user)) {

                $access_to_user = Access::
                where('id_reader', $id_user)
                    ->where('id_author', Auth::id())
                    ->select('*')
                    ->first();

                $access_to_me = Access::
                where('id_reader', Auth::id())
                    ->where('id_author', $id_user)
                    ->select('*')
                    ->first();

                $request->access_to_user = $access_to_user;
                $request->access_to_me = $access_to_me;

                //abort(403);

        }
        return $next($request);
    }
}
