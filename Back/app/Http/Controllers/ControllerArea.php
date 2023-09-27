<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ControllerArea extends Controller
{
    // Récupérer tous les enregistrements
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas);
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
        return response()->json($area);
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
