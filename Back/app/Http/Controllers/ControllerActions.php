<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Action;
use App\Models\Area;

class ControllerActions extends Controller
{

    public function index()
    {
        $userId = Auth::id();
        $actions = Action::whereHas('area', function ($query) use ($userId) {
            $query->where('users_id', $userId);
        })->get();
        return response()->json($actions);
    }

    // Récupérer une action spécifique liée à une area
    public function show($areaId)
    {
        $userId = Auth::id();
        $area = Area::find($areaId);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $action = Action::where('areas_id', $areaId)->first();

        if (!$action) {
            return response()->json(['message' => 'Action not found for this area'], 404);
        }

        return response()->json($action);
    }

    // Créer une action pour une area donnée
    public function create(Request $request, $areaId)
    {
        $userId = Auth::id();
        $area = Area::find($areaId);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'services_id' => 'required',
                'activated' => 'required',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
        }

        $action = Action::create(array_merge($request->all(), ['areas_id' => $areaId]));
        return response()->json($action, 201);
    }

    // Mettre à jour une action liée à une area
    public function update(Request $request, $areaId)
    {
        $userId = Auth::id();
        $area = Area::find($areaId);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $action = Action::where('areas_id', $areaId)->first();

        if (!$action) {
            return response()->json(['message' => 'Action not found for this area'], 404);
        }

        $action->update($request->all());
        return response()->json($action);
    }

    // Supprimer une action liée à une area
    public function delete($areaId)
    {
        $userId = Auth::id();
        $area = Area::find($areaId);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        if ($area->users_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $action = Action::where('areas_id', $areaId)->first();

        if (!$action) {
            return response()->json(['message' => 'Action not found for this area'], 404);
        }

        $action->delete();
        return response()->json(['message' => 'Action deleted'], 200);
    }
}
