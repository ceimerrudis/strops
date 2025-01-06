<?php

namespace App\Console\Commands;

use App\Imports\ObjectsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SynchronizeObjectData extends Command
{
    protected $signature = 'app:synchronize-object-data';

    protected $description = 'Komanda kas iegūst jaunākos datus no excel faila';

    public function handle()
    {
        $year = Carbon::now()->year;
        $path = '/volume1/Makonis/Atskaites/Objekti darbinieki stundas/Objekti_' . $year . '.xlsx';
        if (!file_exists($path)) {
            Log::error("Excel file does not exist:" . $path);
            $this->error("Excel file does not exist: ". $path);
            throw new \Exception("Object sync failure.");
        }

        try{
            Log::info("Begin object import");
            Excel::import(new ObjectsImport, $path, null, null);
            Log::info("End object import");
        }catch(\Exception $e){
            Log::error("Falied oppenningn file. - ". $e->getMessage());
            throw new \Exception("Object sync failure.");
        }
        return 'Objektu saraksts atjaunināts!';
    }
}
