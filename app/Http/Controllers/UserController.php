<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use DateTimeZone;
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
    }

    public function updateFromUser(User $user){
        //パスワード未入力時は、パスワード情報を変更しない
        if(request('password')){
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
    }

    public function destroy(User $user){
        $user->delete();
    }

    public function search(Request $request){
        $query = User::query();

        //検索
        $search = $request['search'];
        if($search['name']) {
            $this->searchWord($search['name'], 'name', $query);
        }
        if($search['email']) {
            $this->searchWord($search['email'], 'email', $query);
        }
        if($search['created_at_start']){
            //dateTime型に変換し、日本時刻に変換した値で検索
            $date = new DateTime($search['created_at_start']);
            $date->setTimezone( new DateTimeZone('Asia/Tokyo'))->format(DateTime::ISO8601);
            $query->whereDate('created_at', '>=', $date)->get();
        }
        if($search['created_at_end']){
            $date = new DateTime($search['created_at_end']);
            $date->setTimezone( new DateTimeZone('Asia/Tokyo'))->format(DateTime::ISO8601);
            $query->whereDate('created_at', '<=', $date)->get();
        }
        if($search['role'] != ""){    //selectBoxの為
            $query->where('role_id', $search['role']);
        }
        if($search['status'] != ""){    //selectBoxの為
            $query->where('status', $search['status']);
        }

        //ソート
        $sort = $request['sort'];
        if($sort['id']){
            $query->orderBy('id', $sort['id']);
        }
        if($sort['created_at']){
            $query->orderBy('created_at', $sort['created_at']);
        }
        if($sort['status']){
            $query->orderBy('status', $sort['status']);
        }
        if($sort['role']){
            $query->orderBy('role_id', $sort['role']);
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
    }

    public function cancelPremium(User $user){
        if($user->status === 'premium'){
            $user->status = 'normal';
            $user->save();
        }
    }

    public function exist(Request $request){
        return User::find($request['id']);
    }
}
