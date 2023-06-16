<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    use HasFactory;

    public function authAcessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }
}
