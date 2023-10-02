<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Reaction;
use App\Models\Area;
use App\Models\Action;

class ControllerReactions extends Controller
{
    // Récupérer une réaction spécifique liée à une area
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

        $reaction = Reaction::where('actions_id', $action->id)->first();

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found for this action'], 404);
        }

        return response()->json($reaction);
    }

    // Créer une réaction pour une action donnée liée à une area
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

        $action = Action::where('areas_id', $areaId)->first();

        if (!$action) {
            return response()->json(['message' => 'Action not found for this area'], 404);
        }

        try {
            $request->validate([
                'actions_id' => 'required',
                'services_id' => 'required',
                'activated' => 'required',
                // Ajoutez d'autres règles de validation au besoin.
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
        }

        $reaction = Reaction::create(array_merge($request->all(), ['actions_id' => $action->id]));
        return response()->json($reaction, 201);
    }

    // Mettre à jour une réaction liée à une action donnée dans une area
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

        $reaction = Reaction::where('actions_id', $action->id)->first();

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found for this action'], 404);
        }

        $reaction->update($request->all());
        return response()->json($reaction);
    }

    // Supprimer une réaction liée à une action donnée dans une area
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

        $reaction = Reaction::where('actions_id', $action->id)->first();

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found for this action'], 404);
        }

        $reaction->delete();
        return response()->json(['message' => 'Reaction deleted'], 200);
    }
}
