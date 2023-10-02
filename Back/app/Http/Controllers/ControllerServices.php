<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Service;

class ControllerServices extends Controller
{
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
