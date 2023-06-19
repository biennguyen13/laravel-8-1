<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function getUser($id)
    {
        $user = Cache::remember('users-' . $id, 60, function () use ($id) {
            return User::find($id);
        });

        return $user;
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
        $result = User::find((int)$id)?->delete();
        if ($result && Cache::has('users-' . $id)) {
            Cache::forget('users-' . $id);
        }
        return $result;
    }

    public function updateUser($id, $data)
    {
        $result = User::find($id)?->update($data);
        if ($result && Cache::has('users-' . $id)) {
            // Cache::forget('users-' . $id);
            Cache::remember('users-' . $id, 60, function () use ($result) {
                return $result;
            });
        }
        return $result;
    }
}
