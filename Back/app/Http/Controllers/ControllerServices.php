<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Service;
use App\Models\Action;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckTokenService;

class ControllerServices extends Controller
{

    protected $checkTokenService;

    public function __construct(CheckTokenService $checkTokenService)
    {
        $this->checkTokenService = $checkTokenService;
    }

    public function is_token_valid_for_service($serviceNameLower, $validity)
    {
        if (
            strpos($serviceNameLower, 'google') !== false &&
            array_key_exists('google', $validity) &&
            !$validity['google']
        ) {
            return false;
        }
        if (
            strpos($serviceNameLower, 'spotify') !== false &&
            array_key_exists('spotify', $validity) &&
            !$validity['spotify']
        ) {
            return false;
        }
        if (
            strpos($serviceNameLower, 'discord') !== false &&
            array_key_exists('discord', $validity) &&
            !$validity['discord']
        ) {
            return false;
        }
        if (
            strpos($serviceNameLower, 'twitch') !== false &&
            array_key_exists('twitch', $validity) &&
            !$validity['twitch']
        ) {
            return false;
        }
        if (
            strpos($serviceNameLower, 'github') !== false &&
            array_key_exists('github', $validity) &&
            !$validity['github']
        ) {
            return false;
        }
        if (
            strpos($serviceNameLower, 'hour') !== false &&
            array_key_exists('hour', $validity) &&
            !$validity['hour']
        ) {
            return false;
        }
        return true;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $validity = $this->checkTokenService->checkTokensValidity($user);

        $services = Service::all();
        $services_filtered = [];
        for ($i = 0; $i < count($services); $i++) {
            $serviceNameLower = strtolower($services[$i]->service_name);
            if (!$this->is_token_valid_for_service($serviceNameLower, $validity)) {
                continue;
            }
            if (strpos($serviceNameLower, 'hour') !== false) {
                $services[$i]['options'] = [
                    "hour selected"
                ];
            }
            array_push($services_filtered, $services[$i]);
        }
        return response()->json($services_filtered);
    }

    // Récupérer un service spécifique lié à une action
    public function showAction($actionId)
    {
        $action = Action::find($actionId);

        if (!$action) {
            return response()->json(['message' => 'Action not found'], 404);
        }

        $service = Service::find($action->services_id);

        if (!$service) {
            return response()->json(['message' => 'Service not found for this action'], 404);
        }

        return response()->json($service);
    }

    // Récupérer un service spécifique lié à une réaction
    public function showReaction($reactionId)
    {
        $reaction = Reaction::find($reactionId);

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found'], 404);
        }

        $service = Service::find($reaction->services_id);

        if (!$service) {
            return response()->json(['message' => 'Service not found for this reaction'], 404);
        }

        return response()->json($service);
    }

    // Créer un nouveau service
    public function create(Request $request)
    {
        try {
            $request->validate([
                'service_name' => 'required',
                'service_description' => 'required',
                'apikey' => 'required',
                'url' => 'required',
                'working' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
        }

        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    // Mettre à jour un service existant
    public function update(Request $request, $serviceId)
    {
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        try {
            $request->validate([
                'service_name' => 'required',
                'service_description' => 'required',
                'apikey' => 'required',
                'url' => 'required',
                'working' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
        }

        $service->update($request->all());
        return response()->json($service);
    }
}
