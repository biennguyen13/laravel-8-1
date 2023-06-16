<?php

namespace App\Repositories;


use App\Models\User;

class UserRepository
{

    public function getUser($id)
    {
        return User::find($id);
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
        return User::find($id)?->delete();
    }

    public function updateUser($id, $data)
    {
        return User::find($id)?->update($data);
    }
}
