<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Error extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'errors';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'usage_before',
        'usage_after',
        'vehicle_use',
        'reservation',
        'comment',
        'time',
    ];

    protected function casts(): array
    {
        return [
            'time' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function Reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function VehicleUse()
    {
        return $this->belongsTo(VehicleUse::class);
    }

    public function CreateError($data)
    {
        return $this->create($data);
    }

    public function DeleteError($id)
    {
        $error = $this->find($id);
        if ($error) {
            $error->delete();
            return true;
        }
        return false;
    }

    public function GetVehicle($id)
    {
        return $this->find($id);
    }

    public function GetAllErrors()
    {
        return $this->all();
    }
}
