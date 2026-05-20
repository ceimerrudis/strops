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

    public function CreateFakeDevice()
    {
        DeviceInfo::create([
            'user_id' => rand(1, 5),
            'browser_name' => ['Chrome', 'Firefox', 'Safari', 'Edge'][rand(0, 3)],
            'os_name' => ['Windows', 'Android', 'iOS', 'Linux'][rand(0, 3)],
            'screen_width' => rand(320, 1920),
            'screen_height' => rand(480, 1080),
        ]);

        return ":)";
    }

    public function ShowUserLogins()
    {
        $logins = UserLogin::all();
        return view('telemetry_views.user_logins', compact('logins'));
    }

    public function CreateFakeUserLogin()
    {
        UserLogin::create([
            'user_id' => rand(1, 5),
            'logged_in_at' => now(),
            'remember_me' => rand(0, 1),
        ]);

        return ":)";
    }

        public function ShowPages()
    {
        $pages = Page::all();
        return view('telemetry_views.pages', compact('pages'));
    }

    public function CreateFakePage()
    {
        Page::create([
            'name' => ['Home', 'About', 'Contact', 'Products', 'Login'][rand(0, 4)],
        ]);

               return ":)";

    }


    public function ShowButtons()
    {
        $buttons = Button::all();
        return view('telemetry_views.buttons', compact('buttons'));
    }

    public function CreateFakeButton()
    {
        Button::create([
            'name' => ['Buy', 'Submit', 'Cancel', 'Login', 'Register'][rand(0, 4)],
        ]);

        return ":)";

    }


    public function ShowPageMetrics()
    {
        $metrics = PageMetric::all();
        return view('telemetry_views.page_metrics', compact('metrics'));
    }

    public function CreateFakePageMetric()
    {
        PageMetric::create([
            'page_id' => rand(1, 5),
            'device_id' => rand(1, 5),
            'visit_count' => rand(1, 50),
            'avg_load_time' => rand(100, 2000),
            'max_load_time' => rand(200, 5000),
        ]);

        return ":)";
    }

    public function ShowButtonClicks()
    {
        $clicks = ButtonClick::all();
        return view('telemetry_views.button_clicks', compact('clicks'));
    }

    public function CreateFakeButtonClicks()
    {
        ButtonClick::create([
            'user_id' => rand(1, 5),
            'button_id' => rand(28, 30),
            'clicked_at' => now(),
        ]);

        return ":)";
    }


}
