<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function getUsers(){
        $where = [
            'is_deleted' => 0,
        ];
        $columns = [
            'id',
            'name',
            'age',
            'family_code'
        ];
        $users = User::where($where)->get($columns);
        return $users;
    }

    public function getAll(){
        $users = User::all();
        return $users;
    }

    public function ageCount(){

        $sum = User::where('is_deleted', 0)->sum("age");
        return $sum;
    }
}
