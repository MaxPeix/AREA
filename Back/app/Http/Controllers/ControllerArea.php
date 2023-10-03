<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Action;
use App\Models\Service;
use App\Models\Reaction;

class ControllerArea extends Controller
{
    // Récupérer tous les enregistrements
    public function index()
    {
        $userId = Auth::id();
        $areas = Area::where('users_id', $userId)->get();

        $to_return = [];
        foreach ($areas as $area) {
            $action = Action::where('areas_id', $area->id)->first();
            $to_return_action = [];
            if ($action) {
                $service = Service::find($action->services_id);
                $to_return_service = [];
                if ($service) {
                    $to_return_service = [
                        'id' => $service->id,
                        'service_name' => $service->service_name,
                        'service_description' => $service->service_description,
                        'url' => $service->url,
                        'working' => $service->working
                    ];
                }
                $to_return_action = [
                    'id' => $action->id,
                    'name' => $action->name,
                    'description' => $action->description,
                    'activated' => $action->activated,
                    'services' => $to_return_service ?? [],
                ];
                $reaction = Reaction::where('actions_id', $action->id)->first();
                $to_return_reaction = [];
                if ($reaction) {
                    $service = Service::find($reaction->services_id);
                    $to_return_service = [];
                    if ($service) {
                        $to_return_service = [
                            'id' => $service->id,
                            'service_name' => $service->service_name,
                            'service_description' => $service->service_description,
                            'url' => $service->url,
                            'working' => $service->working
                        ];
                    }
                    $to_return_reaction = [
                        'id' => $reaction->id,
                        'activated' => $reaction->activated,
                        'action_id' => $action->id,
                        'services' => $to_return_service ?? [],
                    ];
                }
            }
            $to_return[] = [
                'id' => $area->id,
                'name' => $area->name,
                'description' => $area->description,
                'activated' => $area->activated,
                'action' => $to_return_action ?? [],
                'reaction' => $to_return_action && $to_return_reaction ? $to_return_reaction : [],
            ];
        }
        return response()->json($to_return);
    }

    // Créer un nouvel enregistrement
    public function create(Request $request)
    {
        $userId = Auth::id();
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'activated' => 'required',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
        }
        $area = Area::create(array_merge($request->all(), ['users_id' => $userId]));
        return response()->json($area, 201);
    }

    // Récupérer un enregistrement spécifique
    public function show($id)
    {
        $userId = Auth::id();
        $area = Area::find($id);
        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }   

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $action = Action::where('areas_id', $area->id)->first();
        $to_return_action = [];
        if ($action) {
            $service = Service::find($action->services_id);
            $to_return_service = [];
            if ($service) {
                $to_return_service = [
                    'id' => $service->id,
                    'service_name' => $service->service_name,
                    'service_description' => $service->service_description,
                    'url' => $service->url,
                    'working' => $service->working
                ];
            }
            $to_return_action = [
                'id' => $action->id,
                'name' => $action->name,
                'description' => $action->description,
                'activated' => $action->activated,
                'services' => $to_return_service ?? [],
            ];
            $reaction = Reaction::where('actions_id', $action->id)->first();
            $to_return_reaction = [];
            if ($reaction) {
                $service = Service::find($reaction->services_id);
                $to_return_service = [];
                if ($service) {
                    $to_return_service = [
                        'id' => $service->id,
                        'service_name' => $service->service_name,
                        'service_description' => $service->service_description,
                        'url' => $service->url,
                        'working' => $service->working
                    ];
                }
                $to_return_reaction = [
                    'id' => $reaction->id,
                    'activated' => $reaction->activated,
                    'action_id' => $action->id,
                    'services' => $to_return_service ?? [],
                ];
            }
        }
        $to_return[] = [
            'id' => $area->id,
            'name' => $area->name,
            'description' => $area->description,
            'activated' => $area->activated,
            'action' => $to_return_action ?? [],
            'reaction' => $to_return_action && $to_return_reaction ? $to_return_reaction : [],
        ];
        return response()->json($to_return);
    }

    // Mettre à jour un enregistrement
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $area = Area::find($id);
        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $area->update($request->all());
        return response()->json($area);
    }

    // Supprimer un enregistrement
    public function delete($id)
    {
        $userId = Auth::id();
        $area = Area::find($id);
        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $area->delete();
        return response()->json(['message' => 'Area deleted'], 200);
    }
}
