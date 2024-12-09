<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'username',
        'password',
        'name',
        'lname',
        'type',
        'lastUsedVehicle',
        'lastUsedObject',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'password' => 'hashed',
        ];
    }

    public function Reservations()
    {
        return $this->hasMany(Reservation::class, 'user');
    }

    public function VehicleUses()
    {
        return $this->hasMany(VehicleUse::class, 'user');
    }

    public function CreateUser($data)
    {
        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->create($data);
    }

    public function UpdateUser($id, $data)
    {
        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function DeleteUser($id)
    {
        $user = $this->find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function GetUser($id)
    {
        return $this->find($id);
    }

    public function GetAllUsers()
    {
        return $this->all();
    }
}
