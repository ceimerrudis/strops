<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ButtonClick extends Model
{
    protected $table = 'button_clicks';

    protected $fillable = [
        'button_id',
        'user_id',
        'press_count',
    ];
    public $timestamps = false;

    public function button()
    {
        return $this->belongsTo(Button::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function CreateButtonClicks($data)
    {
        return $this->create($data);
    }

    public function GetButtonClicks($id)
    {
        return $this->find($id);
    }

    public function GetAllButtonClicks()
    {
        return $this->all();
    }
}
