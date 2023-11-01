<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Action;
use App\Models\Service;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ControllerArea extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $areas = Area::with([
            'actions.service',
            'actions.reactions',
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
                        'reactions' => $action->reactions->map(function ($reaction) {
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
            try {
                $request->validate([
                    'name' => 'required',
                    'description' => 'required',
                    'service_action_id' => 'required|int',
                    'config' => 'required|array',
                    'service_reaction_id' => 'required|int',
                    'activated' => 'required',
                ]);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors()->getMessages();
                return response()->json(['message' => 'Invalid data', 'errors' => $errors], 401);
            }

            if ($request->service_reaction_id == 14) {
                if (!filter_var($request->config[2], FILTER_VALIDATE_EMAIL)) {
                    return response()->json(['message' => 'Receiver of the mail is not a valid email address'], 401);
                }
                if ($request->config[3] == null) {
                    return response()->json(['message' => 'Content of the mail invalid'], 401);
                }
            }

            if ($request->service_reaction_id == 21) {
                if ($request->config[2] == null) {
                    return response()->json(['message' => 'Invalid content of file'], 401);
                }
            }

            if ($request->service_reaction_id == 22) {
                if ($request->config[2] == null) {
                    return response()->json(['message' => 'Invalid repository name'], 401);
                }
                if ($request->config[3] == null) {
                    return response()->json(['message' => 'Invalid content of the issue'], 401);
                }
            }

            if ($request->service_reaction_id == 23) {
                if ($request->config[2] == null) {
                    if (strlen($request->config[0]) > 35 || strlen($request->config[0]) < 1) {
                        return response()->json(['message' => 'Invalid new title of file'], 401);
                    }
                }
            }

            if ($request->service_action_id == 18) {
                if ($request->config[0] == null) {
                    return response()->json(['message' => 'Invalid hour minute selection format is HH:MM'], 401);
                } else {
                    if (!preg_match("/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/", $request->config[0])) {
                        return response()->json(['message' => 'Invalid hour minute selection format is HH:MM'], 401);
                    }
                }
                User::where('id', $userId)->update(['hour_selected' => $request->config[0]]);
            }

            if ($request->service_action_id == 24) {
                if ($request->config[0] == null) {
                    return response()->json(['message' => 'Please choose a valid crypto'], 401);
                }
                if ($request->config[1] == null) {
                    return response()->json(['message' => 'Please choose a valid price'], 401);
                }
                if (!preg_match('/^\d+(\.\d+)?$/', $request->config[1])) {
                    return response()->json(['message' => 'Price must be a valid number'], 401);
                }
                $pair = strtoupper($request->config[0]) . 'USDT';
                $response = Http::withOptions([
                    'verify' => false
                ])->get("https://api.binance.com/api/v3/ticker/price?symbol=$pair");
                if ($response->failed()) {
                    return response()->json(['message' => 'Please choose a valid crypto.'], 401);
                }
            }

            if ($request->service_action_id == 25) {

                if ($request->config[0] == null) {
                    return response()->json(['message' => 'Please choose a valid city'], 401);
                }
                if ($request->config[1] == null) {
                    return response()->json(['message' => 'Please choose a valid temperature'], 401);
                }
                if (!preg_match('/^\d+(\.\d+)?$/', $request->config[1])) {
                    return response()->json(['message' => 'Temperature must be a valid number'], 401);
                }
                $city = $request->config[0];
                $response = Http::withOptions([
                    'verify' => false
                ])->get("https://wttr.in/$city");

                if ($response->status() == 404) {
                    return response()->json(['message' => 'Please choose a valid city.'], 401);
                }
            }

            if ($request->service_action_id == 26) {
                if ($request->config[0] == null) {
                    return response()->json(['message' => 'Please choose a valid city'], 401);
                }
                if ($request->config[1] == null) {
                    return response()->json(['message' => 'Please choose a valid humidity percentage'], 401);
                }
                if (!preg_match('/^\d+(\.\d+)?$/', $request->config[1])) {
                    return response()->json(['message' => 'Humidity must be a valid percentage number'], 401);
                }
                $city = $request->config[0];
                $response = Http::withOptions([
                    'verify' => false
                ])->get("https://wttr.in/$city");

                if ($response->status() == 404) {
                    return response()->json(['message' => 'Please choose a valid city.'], 401);
                }
            }

            if ($request->service_action_id == 19 || $request->service_action_id == 20) {
                if ($request->config[0] == null) {
                    return response()->json(['message' => 'Invalid github repository'], 401);
                }
                if (substr_count($request->config[0], '/') !== 1) {
                    return response()->json(['message' => 'Invalid github repository, there should be exactly one "/" in the name'], 401);
                }
            }

            $area = Area::create(array_merge($request->all(), ['users_id' => $userId]));
            $actionData = [
                'services_id' => $request->service_action_id,
                'areas_id' => $area->id,
                'first_parameter' => $request->config[0] ?? null,
                'second_parameter' => $request->config[1] ?? null,
                'activated' => $request->activated
            ];
            $action = Action::create($actionData);

            $reactionData = [
                'actions_id' => $action->id,
                'services_id' => $request->service_reaction_id,
                'first_parameter' => $request->config[2] ?? null,
                'second_parameter' => $request->config[3] ?? null,
                'activated' => $request->activated
            ];
            $reaction = Reaction::create($reactionData);

            $finalResult = [
                'area' => $area,
                'action' => $action,
                'reaction' => $reaction,
            ];
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Message: ' . $th->getMessage()], 401);
        }

        return response()->json($finalResult, 201);
    }

    public function show($id)
    {
        $userId = Auth::id();

        $areas = Area::with([
            'actions.service',
            'actions.reactions',
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
                        'reactions' => $action->reactions->map(function ($reaction) {
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

        $action = Action::where('areas_id', $id)->first();
        if ($action) {
            $reaction = Reaction::where('actions_id', $action->id)->first();
            if ($reaction) {
                $reaction->delete();
            }

            $action->delete();
        }
    
        $area->delete();

        return response()->json(['message' => 'Area and associated records deleted'], 200);
    }
}
