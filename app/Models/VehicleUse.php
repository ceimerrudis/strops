<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleUse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle_uses';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user',
        'vehicle',
        'object',
        'usage',
        'comment',
        'from',
        'until',
        'usage_before',
        'usage_after',
    ];

    protected function casts(): array
    {
        return [
            'from' => 'datetime:Y-m-d H:i',
            'until' => 'datetime:Y-m-d H:i',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function Object()
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function CreateVehicleUse($data)
    {
        return $this->create($data);
    }

    public function UpdateVehicleUse($id, $data)
    {
        $vehicleUse = $this->find($id);
        if ($vehicleUse) {
            $vehicleUse->update($data);
            return $vehicleUse;
        }
        return null;
    }

    public function DeleteVehicleUse($id)
    {
        $vehicleUse = $this->find($id);
        if ($vehicleUse) {
            $vehicleUse->delete();
            return true;
        }
        return false;
    }

    public function GetVehicleUse($id)
    {
        return $this->find($id);
    }

    public function GetAllVehicleUses()
    {
        return $this->all();
    }
}
