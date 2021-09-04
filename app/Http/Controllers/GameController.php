<?php

namespace App\Http\Controllers;


use App\Http\Requests\ConnectGameRequest;
use App\Http\Requests\GestureRequest;
use App\Http\Requests\NewGameRequest;
use App\Models\Game;

class GameController extends Controller
{
    public function getOpen(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Game::all()->where('is_over',false));
    }

    public function getOver(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Game::all()->where('is_over',true));
    }

    public function newGame(NewGameRequest $request): \Illuminate\Http\JsonResponse
    {
        $game = new Game();
        if($game->save()) {
            $game->addPlayer($request->get('nickname'));
            return response()->json(['success' => true, 'message' => 'Game successfully created']);
        }

        return response()->json(['success' => false, 'message' => 'Something want wrong']);
    }

    public function connectToGame(ConnectGameRequest $request): \Illuminate\Http\JsonResponse
    {
        $game = Game::findOrFail($request->get('game_id'));

        return response()->json($game->addPlayer($request->get('nickname')));
    }

    public function chooseGesture(GestureRequest $request): \Illuminate\Http\JsonResponse
    {
        $game = Game::findOrFail($request->get('game_id'));

        return response()->json($game->gesture($request->get('nickname'),$request->get('gesture')));
    }

    public function getGame(GameRequest $request): \Illuminate\Http\JsonResponse
    {
        $game = Game::findOrFail($request->get('game_id'));

        return response()->json($game->getInfo());
    }
}
