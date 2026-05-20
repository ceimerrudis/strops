<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMetric extends Model
{
    protected $table = 'page_metrics';

    protected $fillable = [
        'page_id',
        'device_id',
        'visit_count',
        'avg_load_time',
        'max_load_time',
    ];
    public $timestamps = false;

    public function UpdatePageMetric($id, $data)
    {
        $metric = $this->find($id);
        if ($metric) {
            $metric->update($data);
            return $metric;
        }
        return null;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function device()
    {
        return $this->belongsTo(DeviceInfo::class, 'device_id');
    }
    public function CreatePageMetric($data)
    {
        return $this->create($data);
    }

    public function GetPageMetric($id)
    {
        return $this->find($id);
    }

    public function GetAllPageMetrics()
    {
        return $this->all();
    }
}
