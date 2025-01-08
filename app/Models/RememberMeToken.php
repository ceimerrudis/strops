<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RememberMeToken extends Model
{
    use HasFactory;

    protected $table = 'remember_me_tokens';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $fillable = [
        'user',
        'token',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function CreateToken($data)
    {
        return $this->create($data);
    }

    public function DeleteToken($id)
    {
        $token = $this->find($id);
        if ($token) {
            $token->delete();
            return true;
        }
        return false;
    }

    public function GetToken($id)
    {
        return $this->find($id);
    }

    public function GetAllTokens()
    {
        return $this->all();
    }
}
