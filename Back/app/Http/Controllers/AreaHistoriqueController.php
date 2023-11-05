<?php

namespace App\Http\Controllers;

use App\Models\AreaHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AreaHistoriqueController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $areaHistoriques = AreaHistorique::with([])
            ->where('users_id', $userId)
            ->get();

        return response()->json($areaHistoriques, 200);
    }

    public function show($id)
    {
        $userId = Auth::id();
        $areaHistorique = AreaHistorique::where('id', $id)->where('users_id', $userId)->first();

        if (!$areaHistorique) {
            return response()->json(['message' => 'Not found or unauthorized'], 404);
        }

        return response()->json($areaHistorique, 200);
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'users_id' => 'required',
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'informations_random' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 401);
        }

        $areaHistorique = AreaHistorique::create($validatedData);
        return response()->json($areaHistorique, 201);
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $areaHistorique = AreaHistorique::where('id', $id)->where('users_id', $userId)->first();

        if (!$areaHistorique) {
            return response()->json(['message' => 'Not found or unauthorized'], 404);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'informations_random' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['message' => 'Validation failed', 'errors' => $errors], 401);
        }

        $areaHistorique->update($validatedData);
        return response()->json($areaHistorique, 200);
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $areaHistorique = AreaHistorique::where('id', $id)->where('users_id', $userId)->first();

        if (!$areaHistorique) {
            return response()->json(['message' => 'Not found or unauthorized'], 404);
        }

        $areaHistorique->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
