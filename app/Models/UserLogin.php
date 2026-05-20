<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table = 'user_logins';

    protected $fillable = [
        'user_id',
        'logged_in_at',
        'remember_me',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function CreateUserLogin($data)
    {
        return $this->create($data);
    }

    public function GetUserLogin($id)
    {
        return $this->find($id);
    }

    public function GetAllUserLogins()
    {
        return $this->all();
    }
}
