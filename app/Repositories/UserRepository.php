<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function getUser($id)
    {
        return Cache::remember('users-' . $id, 60, function () use ($id) {
            return User::find($id);
        });
    }

    public function createUser($name, $email, $password)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->save();

        return $user;
    }

    public function deleteUser($id)
    {
        Cache::forget('users-' . $id);
        return User::find((int)$id)?->delete();
    }

    public function updateUser($id, $data)
    {
        Cache::forget('users-' . $id);
        return  User::find($id)?->update($data);
    }
}
