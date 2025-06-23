<!DOCTYPE html>
<html>
<head>
    <title>Galgje</title>
</head>
<body>
    <h1>Galgje</h1>
    <h2>Won games: {{auth()->user()->wonGames}}</h2>
    @if (!isset($game))
        <form method="POST" action="{{ route('galgje.start') }}">
            @csrf
            <button type="submit">Nieuw Spel Starten</button>
        </form>
    @else
        <div>
            @foreach (str_split($game->word) as $char)
                {{ str_contains($game->guessed_letters, $char) ? $char : '_' }}
            @endforeach
        </div>

        <div>
            @foreach (range('a', 'z') as $letter)
                <form method="POST" action="{{ route('galgje.guess', ['game' => $game->id]) }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="letter" value="{{ $letter }}">
                    <button type="submit" 
                        {{ str_contains($game->guessed_letters, $letter) || $game->is_finished ? 'disabled' : '' }}>
                        {{ strtoupper($letter) }}
                    </button>
                </form>
            @endforeach
        </div>

        <div>
            <p>Foute pogingen: {{ $game->incorrect_guesses }} / 12</p>

            @if ($game->is_finished)
                @if ($game->is_won)
                    <p>Je hebt gewonnen!</p>
                @else
                    <p>Verloren. Het woord was: <strong>{{ $game->word }}</strong></p>
                @endif
                <form method="POST" action="{{ route('galgje.start') }}">
                    @csrf
                    <button type="submit">Opnieuw Spelen</button>
                </form>
            @endif
        </div>
    @endif
</body>
</html>
