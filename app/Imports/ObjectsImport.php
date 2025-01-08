<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\ObjectModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ObjectsImport implements ToModel, WithCalculatedFormulas, WithStartRow, WithMultipleSheets 
{
    private $stopImport = false;
    
    public function model(array $row)
    {
        if ($this->stopImport) {
            return null;
        }
        if($row[0] == null) {$this->stopImport = true; return null;}
        
        if($row[4] == "aktīvs"){
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
        if(ObjectModel::where('code', $row[1])->exists()) {
            $object = ObjectModel::where('code', $row[1])->first();
            if($object->active){
                Log::info("Changing statuss of object to inactive.");
                Log::info($row);
                Log::info($object);

                $object->active = false;
                $object->save();
            }
        }
        return null;
    }

    public function startRow(): int
    {
        //Pirmā rinda it datu info
        return 2;
    }
    public function sheets(): array
    {
        return [
            'Objekti' => new ObjectsImport()
        ];
    }
}
