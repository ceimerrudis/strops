<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reports';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'object',
        'progress',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function Object()
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function CreateReport($data)
    {
        return $this->create($data);
    }

    public function UpdateReport($id, $data)
    {
        $report = $this->find($id);
        if ($report) {
            $report->update($data);
            return $report;
        }
        return null;
    }

    public function DeleteReport($id)
    {
        $report = $this->find($id);
        if ($report) {
            $report->delete();
            return true;
        }
        return false;
    }

    public function GetReport($id)
    {
        return $this->find($id);
    }

    public function GetAllReports()
    {
        return $this->all();
    }
}
