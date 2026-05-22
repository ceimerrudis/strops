<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\DeviceInfo;
use App\Models\Page;
use App\Models\PageMetric;
use App\Models\Button;
use App\Models\ButtonClick;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TelemetryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request)->withoutCookie('telemetry_cookie');
    
        // Nolasa telemetry cookie
        $telemetryJson = $request->cookie('telemetry_cookie');
        if (!$telemetryJson) {
            return $response;
        }

        $data = json_decode($telemetryJson, true);
        if (!is_array($data)) {
            return $response;
        }
        
        if (!empty($data['cookie_id'])) { 
            $cacheKey = 'telemetry_' . $data['cookie_id'];
            if (Cache::has($cacheKey)) {
                return $response; 
            }
            Cache::put($cacheKey, true, now()->addDays(30));
        }
        else {
            return $response; 
        }
        
        // Izveido/atrod DeviceInfo (precīza kopija)
        $device = $this->findOrCreateDeviceInfo(
            auth()->id(),
            $request->userAgent(),
            $data
        );

        // Saglabā PageMetric (updateOrCreate)
        $this->savePageMetrics($data, $device);

        // Saglabā ButtonMetric
        $this->saveButtonMetrics($data);

        return $response;
    }

    private function findOrCreateDeviceInfo($userId, $device, $data)
    {
        if (!$device || empty($data['screen_width']) || empty($data['screen_height'])) { // Ja kādus datus nevar atrast, neizveido ierakstu
            return null;
        }
        
        $width = $data['screen_width'];
        $height = $data['screen_height'];
        
        $browser = "unknown";
        $os = "unknown";
        $open = strpos($device, '(');
        $close = strpos($device, ')');
        if($open !== false && $close !== false && $close > $open)
        {
            $os = trim(substr($device, $open + 1, $close - $open - 1));
            $browser = trim(substr($device, 0, $open)) . trim(substr($device, $close + 1));
        }else {
            return null;
        }

        // Meklē precīzu kopiju
        $device = DeviceInfo::where([
            'user_id' => $userId,
            'browser_name' => $browser,
            'os_name' => $os,
            'screen_width' => $width,
            'screen_height' => $height,
        ])->first();

        // Ja neatrod, izveido jaunu ierakstu
        if (!$device) {
            $device = DeviceInfo::create([
                'user_id' => $userId,
                'browser_name' => $browser,
                'os_name' => $os,
                'screen_width' => $width,
                'screen_height' => $height,
            ]);
        }

        return $device;
    }

    private function savePageMetrics(array $data, $device)
    {
        if (!$device) {
            return;
        }

        if (empty($data['page_name']) || empty($data['dom_ready_ms'])) {
            return;
        }

        $domTime = (double)$data['dom_ready_ms'];

        // Noņem sākuma slīpsvītru
        $pageName = ltrim($data['page_name'], '/');

        // Atrod lapu DB
        $page = Page::where('name', $pageName)->first();
        if (!$page) {
            return;
        }

        // Atrod esošo PageMetric ierakstu
        $metric = PageMetric::where('page_id', $page->id)
                            ->where('device_id', $device->id)
                            ->first();

        // Ja nav ieraksta, izveido jaunu
        if (!$metric) {
            PageMetric::create([
                'page_id' => $page->id,
                'device_id' => $device->id,
                'visit_count' => 1,
                'avg_load_time' => (double)$domTime,
                'max_load_time' => (double)$domTime,
            ]);
            return;
        }

        // Ja ieraksts eksistē , aprēķina jauno vidējo un max
        $oldCount = $metric->visit_count; // Esošais apmeklējumu skaits
        $oldAvg = (double)$metric->avg_load_time; // Esošais lādēšanas laika vidējais
        $oldMax = (double)$metric->max_load_time; // Esošais max lādēšanas laiks

        $newCount = $oldCount + 1; // Jaunais apmeklējumu skaits

        // Jaunais vidējais
        $newAvg = (($oldAvg * $oldCount) + $domTime) / $newCount;

        // Jaunais max
        $newMax = max($oldMax, $domTime);

        // Saglabā
        $metric->update([
            'visit_count' => $newCount,
            'avg_load_time' => (double)$newAvg,
            'max_load_time' => (double)$newMax,
        ]);
    }
    private function saveButtonMetrics(array $data)
    {
        if (empty($data['button_log'])) {
            return;
        }
        //dd($data['button_log']);
        foreach ($data['button_log'] as $button_data)  {
            if (empty($button_data['button_name']) || $button_data['button_name'] == "unknown") {
                continue;
            }

            $userId = auth()->id();

            // atrod pogu pēc nosaukuma
            $button = Button::where('name', $button_data['button_name'])->first();
            if (!$button) {
                continue;
            }

            // atrod esošo ierakstu
            $metric = ButtonClick::where('user_id', $userId)
                                 ->where('button_id', $button->id)
                                 ->first();

            // ja nav, izveido
            if (!$metric) {
                ButtonClick::create([
                    'user_id' => $userId,
                    'button_id' => $button->id,
                    'press_count' => 1,
                ]);
                continue;
            }
            
            // ja ir, pieskaita klāt
            $metric->update([
                'press_count' => $metric->press_count + 1
            ]);
            Log::debug($data['button_log']);
        }
    }
}

