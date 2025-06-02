<?php


namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // ✅ API: Start een nieuw spel (JSON response)
   public function bladeGame(Request $request)
{
    // Haal game id uit sessie of query, bijvoorbeeld:
    $gameId = $request->session()->get('game_id');

    $game = null;
    if ($gameId) {
        $game = Game::find($gameId);
    }

    return view('galgje', compact('game'));
}

public function startGameBlade(Request $request)
{
   $words = [
    'appel', 'boom', 'fiets', 'huis', 'stoel', 'tafel', 'raam', 'deur', 'kat', 'hond',
    'water', 'vuur', 'lucht', 'aarde', 'zon', 'maan', 'ster', 'regen', 'wind', 'berg',
    'zee', 'strand', 'bos', 'bloem', 'gras', 'auto', 'trein', 'boot', 'vlinder', 'vis',
    'ijs', 'brood', 'kaas', 'melk', 'taart', 'appelmoes', 'stoel', 'jas', 'schoen', 'broek',
    'fiets', 'vogel', 'paard', 'konijn', 'muis', 'slang', 'leeuw', 'tijger', 'olifant',
    'school', 'boek', 'pen', 'potlood', 'papier', 'lamp', 'klok', 'telefoon', 'computer',
    'stoel', 'tafel', 'kamer', 'huis', 'tuin', 'straat', 'winkel', 'markt', 'restaurant',
    'koffie', 'thee', 'melk', 'suiker', 'zout', 'peper', 'olie', 'azijn', 'bier', 'wijn',
    'feest', 'muziek', 'dans', 'zang', 'film', 'foto', 'reis', 'vakantie', 'zonsondergang',
    'vriend', 'familie', 'school', 'werk', 'tijd', 'dag', 'nacht', 'jaar', 'seizoen', 'maand'
];

    $word = $words[array_rand($words)]; 

    $game = Game::create([
        'word' => $word,
        'guessed_letters' => '',
        'incorrect_guesses' => 0,
        'is_finished' => false,
        'is_won' => null,
    ]);

    // Sla game id op in sessie zodat bladeGame hem kan laden
    $request->session()->put('game_id', $game->id);

    return redirect()->route('galgje.view');
}

public function guessLetterBlade(Request $request, Game $game)
{
    $letter = strtolower($request->input('letter'));

    if (!str_contains($game->guessed_letters, $letter) && !$game->is_finished) {
        $game->guessed_letters .= $letter;

        if (!str_contains($game->word, $letter)) {
            $game->incorrect_guesses++;
        }

        $allGuessed = collect(str_split($game->word))
            ->every(fn($c) => str_contains($game->guessed_letters, $c));

        if ($allGuessed) {
            $game->is_finished = true;
            $game->is_won = true;
        } elseif ($game->incorrect_guesses >= 12) {
            $game->is_finished = true;
            $game->is_won = false;
        }

        $game->save();
    }

    // Optioneel opnieuw opslaan in sessie:
    $request->session()->put('game_id', $game->id);

    return redirect()->route('galgje.view');
}

}


