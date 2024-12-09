<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicles';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'usage',
        'usage_type',
    ];

    protected function casts(): array
    {
        return [
            'usage_type' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function Reservations()
    {
        return $this->hasMany(Reservation::class, 'vehicle');
    }

    public function VehicleUses()
    {
        return $this->hasMany(VehicleUse::class, 'vehicle');
    }

    public function CreateVehicle($data)
    {
        return $this->create($data);
    }

    public function UpdateVehicle($id, $data)
    {
        $vehicle = $this->find($id);
        if ($vehicle) {
            $vehicle->update($data);
            return $vehicle;
        }
        return null;
    }

    public function DeleteVehicle($id)
    {
        $vehicle = $this->find($id);
        if ($vehicle) {
            $vehicle->delete();
            return true;
        }
        return false;
    }

    public function GetVehicle($id)
    {
        return $this->find($id);
    }

    public function GetAllVehicles()
    {
        return $this->all();
    }
}
