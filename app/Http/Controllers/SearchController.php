<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {
        $name = htmlspecialchars($request->name);
        if (isset($name)) {
            $users = User::where('name', 'LIKE', "%{$name}%")
                ->orderBy('name')->paginate(10);
            return view('search', compact('users'));
        } else {
            $users = User::orderBy('name')->paginate(10);
            return view('search', compact('users'));
        }
    }
}
