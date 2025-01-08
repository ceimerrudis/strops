<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reservations';
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
        'from',
        'until',
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

    public function CreateReservation($data)
    {
        return $this->create($data);
    }

    public function UpdateReservation($id, $data)
    {
        $reservation = $this->find($id);
        if ($reservation) {
            $reservation->update($data);
            return $reservation;
        }
        return null;
    }

    public function DeleteReservation($id)
    {
        $reservation = $this->find($id);
        if ($reservation) {
            $reservation->delete();
            return true;
        }
        return false;
    }

    public function GetReservation($id)
    {
        return $this->find($id);
    }

    public function GetAllReservations()
    {
        return $this->all();
    }
}
