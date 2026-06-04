<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\ObjectModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ObjectsSheetImport implements ToModel, WithCalculatedFormulas, WithStartRow
{
    private $activeObjects; 
    private $stopImport = false;

    public function __construct(array &$activeObjects)
    {
        $this->activeObjects = &$activeObjects;
    }

    public function startRow(): int { return 2; }

    public function model(array $row)
    {
        if ($this->stopImport) {
            return null;
        }
        if($row[0] == null) {$this->stopImport = true; return null;}

        if ($row[4] == "aktīvs") {
            $this->activeObjects[] = $row[1];
            if(ObjectModel::where('code', $row[1])->exists()) {  
                return null;
            }
            Log::info("Creating object automaticaly");
            $objectModel = new ObjectModel([
                'code' => $row[1],
                'name' => $row[2],
                'active' => true,
            ]);
            
            $objectModel->save();
    
            return $objectModel;
        }
        return null;
    }
}
