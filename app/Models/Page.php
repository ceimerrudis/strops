<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function buttons()
    {
        return $this->hasMany(Button::class);
    }

    public function metrics()
    {
        return $this->hasMany(PageMetrics::class);
    }
    public function CreatePage($data)
    {
        return $this->create($data);
    }

    public function GetPage($id)
    {
        return $this->find($id);
    }

    public function GetAllPages()
    {
        return $this->all();
    }
}
