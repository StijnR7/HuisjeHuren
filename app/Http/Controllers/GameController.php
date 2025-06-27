<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\preGame;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function bladeGame(Request $request)
    {
        $gameId = $request->session()->get('game_id');
        $game = null;

        if ($gameId) {
            $game = Game::find($gameId);
        }

        return view('galgje', compact('game'));
    }

 public function StartLookingForGame(Request $request)
{
    $userId = auth()->user()->id;

    $preGames = preGame::all();
    foreach ($preGames as $preGame) {
        if ($preGame->players < 2 && $preGame->plr1 !== $userId) {
            $preGame->players++;
            $preGame->plr2 = $userId;
            $preGame->save();

            return redirect()->route('galgje.realStartGame', [
                'id1' => $preGame->plr1,
                'id2' => $userId,
                'preGameId' => $preGame->id,
            ]);
        }
    }

    $created = preGame::create([
        'players' => 1,
        'plr1' => $userId,
    ]);

    return redirect()->route('galgje.waitForPlayer', ['id' => $created->id]);
}

public function waitForPlayer($id)
{
    $preGame = preGame::findOrFail($id);

    if ($preGame->is_started && $preGame->game_id) {
        session()->put('game_id', $preGame->game_id);
        return redirect()->route('galgje.view');
    }

    return view('wait', ['preGame' => $preGame]);
}


    public function startGameBlade(Request $request, $id1, $id2, $preGameId)
{
    $words = ['appel', 'boom', 'fiets', 'huis'];
    $word = $words[array_rand($words)];

    $game = Game::create([
        'word' => $word,
        'guessed_letters' => '',
        'incorrect_guesses' => 0,
        'is_finished' => false,
        'is_won' => null,
        'player1' => $id1,
        'player2' => $id2,
    ]);

    $request->session()->put('game_id', $game->id);

    $preGame = preGame::find($preGameId);
    $preGame->is_started = true;
    $preGame->game_id = $game->id;
    $preGame->save();

    return redirect()->route('galgje.view');
}

    public function guessLetterBlade(Request $request)
    {
        $game = Game::find($request->input('game_id'));
        $letter = strtolower($request->input('letter'));

        if (!str_contains($game->guessed_letters, $letter) && !$game->is_finished) {
            $game->guessed_letters .= $letter;

            if (!str_contains($game->word, $letter)) {
                $game->incorrect_guesses++;
            }

            $allGuessed = collect(str_split($game->word))->every(fn($c) =>
                str_contains($game->guessed_letters, $c)
            );

            if ($allGuessed) {
                $game->is_finished = true;
                $game->is_won = true;
                auth()->user()->increment('wonGames');
            } elseif ($game->incorrect_guesses >= 12) {
                $game->is_finished = true;
                $game->is_won = false;
            }

            $game->save();
        }

        return response()->json(['success' => true]);
    }

    public function gameData(Request $request)
    {
        $gameId = $request->session()->get('game_id');
        $game = Game::find($gameId);

        return response()->json([
            'guessed_letters' => $game->guessed_letters,
            'incorrect_guesses' => $game->incorrect_guesses,
            'is_finished' => $game->is_finished,
            'is_won' => $game->is_won,
            'word' => $game->word,
        ]);
    }
}
