<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
        $created_at_start = $request->created_at_start;
        $created_at_end = $request->created_at_end;
        $next_update_start = $request->next_update_start;
        $next_update_end = $request->next_update_end;
        $role = $request->role;
        $status = $request->status;

        if($name) {
            $this->searchWord($name, 'name', $query);
        }
        if($email) {
            $this->searchWord($email, 'email', $query);
        }
        if($created_at_start){
            $query->whereDate('created_at', '>=', $created_at_start)->get();
        }
        if($created_at_end){
            $query->whereDate('created_at', '<=', $created_at_end)->get();
        }
        if($next_update_start){
            $query->whereDate('next_update', '>=', $next_update_start)->get();
        }
        if($next_update_end){
            $query->whereDate('next_update', '<=', $next_update_end)->get();
        }
        if($role != ""){
            $role_id = Role::where('name', $role)->first()->id;
            $query->where('role_id', $role_id);
        }
        if($status != ""){
            $query->where('status', $status);
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
