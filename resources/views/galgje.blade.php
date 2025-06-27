<!DOCTYPE html>
<html>
<head>
    <title>Galgje</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Galgje</h1>
    <h2>Won games: {{ auth()->user()->wonGames }}</h2>

    @if (!isset($game))
        <form method="POST" action="{{ route('galgje.start') }}">
            @csrf
            <button type="submit">Nieuw Spel Starten</button>
        </form>
    @else
        <div id="wordDisplay"></div>

        <div id="letterButtons">
            @foreach (range('a', 'z') as $letter)
                <button class="guessBtn" data-letter="{{ $letter }}">{{ strtoupper($letter) }}</button>
            @endforeach
        </div>

        <p id="wrongGuesses"></p>
        <div id="gameResult"></div>

        <form id="restartForm" method="POST" action="{{ route('galgje.start') }}" style="display: none;">
            @csrf
            <button type="submit">Opnieuw Spelen</button>
        </form>

        <script>
            const gameId = {{ $game->id }};
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            function updateGame() {
                fetch('{{ route('galgje.data') }}')
                    .then(response => response.json())
                    .then(data => {
                        const word = data.word.split('').map(char =>
                            data.guessed_letters.includes(char) ? char : '_'
                        ).join(' ');

                        document.getElementById('wordDisplay').textContent = word;
                        document.getElementById('wrongGuesses').textContent = `Foute pogingen: ${data.incorrect_guesses} / 12`;

                        document.querySelectorAll('.guessBtn').forEach(btn => {
                            if (data.guessed_letters.includes(btn.dataset.letter) || data.is_finished) {
                                btn.disabled = true;
                            }
                        });

                        if (data.is_finished) {
                            document.getElementById('gameResult').innerHTML =
                                data.is_won ? '<p>Je hebt gewonnen!</p>' : `<p>Verloren. Het woord was: <strong>${data.word}</strong></p>`;
                            document.getElementById('restartForm').style.display = 'block';
                        }
                    });
            }

            document.querySelectorAll('.guessBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    fetch('{{ route('galgje.guess') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            game_id: gameId,
                            letter: btn.dataset.letter
                        })
                    }).then(() => updateGame());
                });
            });

            setInterval(updateGame, 1000);
            updateGame();
        </script>
    @endif
</body>
</html>
