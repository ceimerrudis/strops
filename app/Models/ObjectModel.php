<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

//Diemžēl šo modeli nevar saukt par Object jo šis nosaukums jau ir rezervēts.
class ObjectModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'objects';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'user_in_charge',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function UserInCharge()
    {
        return $this->belongsTo(User::class);
    }

    public function CreateObject($data)
    {
        return $this->create($data);
    }

    public function UpdateObject($id, $data)
    {
        $object = $this->find($id);
        if ($object) {
            $object->update($data);
            return $object;
        }
        return null;
    }

    public function DeleteObject($id)
    {
        $object = $this->find($id);
        if ($object) {
            $object->delete();
            return true;
        }
        return false;
    }

    public function GetObject($id)
    {
        return $this->find($id);
    }

    public function GetAllObjects()
    {
        return $this->all();
    }
}
