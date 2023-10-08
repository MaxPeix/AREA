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
    public function index()
    {
        $userId = Auth::id();

        $areas = Area::with([
            'actions.service',
            'reactions.service',
            'areaHistorique'
        ])
        ->where('users_id', $userId)
        ->get();

        $to_return = $areas->map(function ($area) {
            return [
                'id' => $area->id,
                'name' => $area->name,
                'description' => $area->description,
                'activated' => $area->activated,
                'action' => $area->actions->map(function ($action) {
                    return [
                        'id' => $action->id,
                        'name' => $action->name,
                        'description' => $action->description,
                        'activated' => $action->activated,
                        'services' => [
                            'id' => $action->service->id,
                            'service_name' => $action->service->service_name,
                            'service_description' => $action->service->service_description,
                            'url' => $action->service->url,
                            'working' => $action->service->working,
                        ],
                    ];
                }),
                'reaction' => $area->reactions->map(function ($reaction) {
                    return [
                        'id' => $reaction->id,
                        'activated' => $reaction->activated,
                        'action_id' => $reaction->actions_id,
                        'services' => [
                            'id' => $reaction->service->id,
                            'service_name' => $reaction->service->service_name,
                            'service_description' => $reaction->service->service_description,
                            'url' => $reaction->service->url,
                            'working' => $reaction->service->working,
                        ],
                    ];
                }),
                'historique' => $area->areaHistorique->map(function ($areaHistorique) {
                    return [
                        'id' => $areaHistorique->id,
                        'name' => $areaHistorique->name,
                        'description' => $areaHistorique->description,
                        'informations_random' => $areaHistorique->informations_random,
                        'created_at' => $areaHistorique->created_at,
                    ];
                }),
            ];
        })->toArray();

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

    public function show($id)
    {
        $userId = Auth::id();

        $areas = Area::with([
            'actions.service',
            'reactions.service',
            'areaHistorique'
        ])
        ->where('id', $id)
        ->where('users_id', $userId)
        ->get();

        $to_return = $areas->map(function ($area) {
            return [
                'id' => $area->id,
                'name' => $area->name,
                'description' => $area->description,
                'activated' => $area->activated,
                'action' => $area->actions->map(function ($action) {
                    return [
                        'id' => $action->id,
                        'name' => $action->name,
                        'description' => $action->description,
                        'activated' => $action->activated,
                        'services' => [
                            'id' => $action->service->id,
                            'service_name' => $action->service->service_name,
                            'service_description' => $action->service->service_description,
                            'url' => $action->service->url,
                            'working' => $action->service->working,
                        ],
                    ];
                }),
                'reaction' => $area->reactions->map(function ($reaction) {
                    return [
                        'id' => $reaction->id,
                        'activated' => $reaction->activated,
                        'action_id' => $reaction->actions_id,
                        'services' => [
                            'id' => $reaction->service->id,
                            'service_name' => $reaction->service->service_name,
                            'service_description' => $reaction->service->service_description,
                            'url' => $reaction->service->url,
                            'working' => $reaction->service->working,
                        ],
                    ];
                }),
                'historique' => $area->areaHistorique->map(function ($areaHistorique) {
                    return [
                        'id' => $areaHistorique->id,
                        'name' => $areaHistorique->name,
                        'description' => $areaHistorique->description,
                        'informations_random' => $areaHistorique->informations_random,
                        'created_at' => $areaHistorique->created_at,
                    ];
                }),
            ];
        })->toArray();

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
