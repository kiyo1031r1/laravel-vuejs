<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $query = User::query();
        $search = $request->searchWord;
        if($search) {
            $search_split = mb_convert_kana($search, 's');
            $search_split = preg_split('/[\s]+/', $search_split, 0, PREG_SPLIT_NO_EMPTY);

            foreach($search_split as $value) {
                $query->where('name', 'like', '%'.$value.'%');
            }
        }
        return $query->get();
    }

    public function show(User $user){
        return $user;
    }

    public function update(Request $request, User $user){
        $user->update($request->all());
        return $user;
    }

    public function destroy(User $user){
        $user->delete();
        return $user;
    }
}
