<?php

namespace App\Http\Controllers;

use App\Models\DeviceInfo;
use App\Models\Page;
use App\Models\PageMetric;
use App\Models\Button;
use App\Models\ButtonClick;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class TelemetryController extends Controller
{
    public function ShowDevices()
    {
        $devices = DeviceInfo::all();
        return view('telemetry_views.devices', compact('devices'));
    }


    public function ShowUserLogins()
    {
        // pēdējo 24h login skaits
        $last24hCount = UserLogin::where('logged_in_at', '>=', now()->subDay())->count(); // subday = 24h atpakaļ

        // procenti, cik izmanto remember_me
        $totalLogins = UserLogin::count();
        $rememberCount = UserLogin::where('remember_me', 1)->count();
        if ($totalLogins > 0) {
            $rememberPercent = ($rememberCount / $totalLogins) * 100;
        } else {
            $rememberPercent = 0;
        }


        // 100 jaunākie ieraksti
        $logins = UserLogin::with('user')
            ->orderBy('logged_in_at', 'desc')
            ->limit(100)
            ->get();

        return view('telemetry_views.user_logins', compact('logins', 'last24hCount', 'rememberPercent'));
    }


    public function ShowPages()
    {
        $pages = Page::all();
        return view('telemetry_views.pages', compact('pages'));
    }


    public function ShowButtons()
    {
        $buttons = Button::all();
        return view('telemetry_views.buttons', compact('buttons'));
    }


    public function ShowPageMetrics()
    {

        $metrics = PageMetric::with(['page', 'device.user'])
            ->orderBy('device_id')
            ->orderBy('avg_load_time', 'desc')
            ->get();

        $highestMaxLoad = PageMetric::with('page')
            ->orderBy('max_load_time', 'desc')
            ->first();

        $highestAvgLoad = PageMetric::with('page')
            ->orderBy('avg_load_time', 'desc')
            ->first();

        $avgMaxLoad = PageMetric::avg('max_load_time');

        return view('telemetry_views.page_metrics', compact('metrics', 'highestMaxLoad', 'highestAvgLoad', 'avgMaxLoad'));
    }



    public function ShowButtonClicks()
    {
        $clicks = ButtonClick::with(['user', 'button'])
            ->orderBy('user_id')
            ->orderBy('button_id')
            ->get();

        return view('telemetry_views.button_clicks', compact('clicks'));
    }



}
