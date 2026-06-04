<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\ObjectModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ObjectsImport implements WithMultipleSheets 
{
    private array $activeObjects = [];
    
    public function sheets(): array
    {
        return [
            'Objekti' => new ObjectsSheetImport($this->activeObjects)
        ];
    }
    
    public function getActiveObjects(): array
    {
        return $this->activeObjects;
    }
}
