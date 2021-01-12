<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(User $user){
        return $user;
    }

    public function update(Request $request, User $user){
        $user->update($request->all());
        return $user;
    }

    public function updateFromUser(User $user){
        //パスワード未入力時は、パスワード情報を変更しない
        if(request('password') !== null){
            $input = Validator::make(request()->all(),[
                'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ])->validate();

            $user->update([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password'])
            ]);
        }
        else{
            $input = Validator::make(request()->all(),[
                'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ])->validate();
            
            $user->update($input);
        }

        return $user;
    }

    public function destroy(User $user){
        $user->delete();
        return $user;
    }

    public function search(Request $request){
        $query = User::query();

        $data = $request->all();
        $search = $data['search'];
        $sort = $data['sort'];

        //検索
        $name = $search['name'];
        $email = $search['email'];
        $created_at_start = $search['created_at_start'];
        $created_at_end = $search['created_at_end'];
        $next_update_start = $search['next_update_start'];
        $next_update_end = $search['next_update_end'];
        $role = $search['role'];
        $status = $search['status'];

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

        //ソート
        $sort_id = $sort['id'];
        $sort_created_at = $sort['created_at'];
        $sort_status = $sort['status'];
        $sort_next_update = $sort['next_update'];
        $sort_role = $sort['role'];

        if($sort_id){
            $query->orderBy('id', $sort_id);
        }
        if($sort_created_at){
            $query->orderBy('created_at', $sort_created_at);
        }
        if($sort_status){
            $query->orderBy('status', $sort_status);
        }
        if($sort_next_update){
            $query->orderBy('next_update', $sort_next_update);
        }
        if($sort_role){
            $query->orderBy('role_id', $sort_role);
        }

        return $query->paginate($sort['per_page']);
    }

    private function searchWord($word, $column, $query){
        $word_split = mb_convert_kana($word, 's');
        $word_split = preg_split('/[\s]+/', $word_split, 0, PREG_SPLIT_NO_EMPTY);

        foreach($word_split as $value) {
            $query->where($column, 'like', '%'.$value.'%');
        }
    }

    public function registerPremium(User $user){
        if($user->status === 'normal'){
            $user->status = 'premium';
            $user->save();
        }
        return $user;
    }
}
