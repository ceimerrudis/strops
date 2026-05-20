<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceInfo extends Model
{
    protected $table = 'device_info';

    protected $fillable = [
        'user_id',
        'browser_name',
        'os_name',
        'screen_width',
        'screen_height',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function CreateDeviceInfo($data)
    {
        return $this->create($data);
    }

    public function GetDeviceInfo($id)
    {
        return $this->find($id);
    }

    public function GetAllDeviceInfo()
    {
        return $this->all();
    }
}
