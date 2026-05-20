<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    protected $table = 'buttons';

    protected $fillable = [
        'page_id',
        'name',
    ];

    public $timestamps = false;
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function clicks()
    {
        return $this->hasMany(ButtonClick::class);
    }
    public function CreateButton($data)
    {
        return $this->create($data);
    }

    public function GetButton($id)
    {
        return $this->find($id);
    }

    public function GetAllButtons()
    {
        return $this->all();
    }
}
