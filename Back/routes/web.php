<?php

use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return "Welcome to the Area Backend";
});

Route::get('/about.json', function (Request $request) {
    $clientIp = $request->ip();
    $currentTime = time();

    $services = Service::all()->toArray();

    $serviceApplications = [];

    foreach ($services as $service) {
        $allowedApps = ['spotify', 'google', 'github', 'discord', 'reached', 'dropbox'];
        $appName = '';

        foreach ($allowedApps as $allowedApp) {
            if (stripos($service['service_name'], $allowedApp) !== false) {
                $appName = $allowedApp;
                break;
            }
        }

        if (empty($appName)) {
            continue;
        }

        $serviceType = (strpos($service['service_name'], '[ACTION]') !== false) ? 'actions' : 'reactions';

        if (!isset($serviceApplications[$appName])) {
            $serviceApplications[$appName] = [
                'name' => ucfirst($appName),
                'actions' => [],
                'reactions' => []
            ];
        }

        $serviceApplications[$appName][$serviceType][] = [
            'name' => $service['service_name'],
            'description' => $service['service_description']
        ];
    }

    $serviceApplications = array_values($serviceApplications);

    $aboutJson = [
        'client' => [
            'host' => $clientIp
        ],
        'server' => [
            'current_time' => $currentTime,
            'services' => $serviceApplications
        ]
    ];

    return response()->json($aboutJson);
});