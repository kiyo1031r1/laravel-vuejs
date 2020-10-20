<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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

    public function search(Request $request){
        $query = User::query();
        $name = $request->name;
        $email = $request->email;

        if($name) {
            // $name_split = mb_convert_kana($name, 's');
            // $name_split = preg_split('/[\s]+/', $name_split, 0, PREG_SPLIT_NO_EMPTY);

            // foreach($name_split as $value) {
            //     $query->where('name', 'like', '%'.$value.'%');
            // }
            $this->searchWord($name, 'name', $query);
        }
        if($email) {
            $this->searchWord($email, 'email', $query);
        }


        return $query->paginate($request->per_page);
    }

    private function searchWord($word, $column, $query){
        $word_split = mb_convert_kana($word, 's');
        $word_split = preg_split('/[\s]+/', $word_split, 0, PREG_SPLIT_NO_EMPTY);

        foreach($word_split as $value) {
            $query->where($column, 'like', '%'.$value.'%');
        }
    }
}
