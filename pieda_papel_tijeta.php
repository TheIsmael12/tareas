<!--- Realizar el juego de piedra, papel o tijera --->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piedra, Papel y Tijeras</title>
</head>

<body>

    <?php
    define('PIEDRA1', "&#x1F91C;");
    define('PIEDRA2', "&#x1F91B;");
    define('TIJERAS', "&#x1F596;");
    define('PAPEL', "&#x1F91A;");

    // Función que maneja el juego y devuelve las jugadas y el ganador
    function jugarJuego()
    {
        $posibilidades = ["piedra", "papel", "tijera"];

        // Jugada aleatoria para ambos jugadores
        $jugador1 = $posibilidades[rand(0, 2)];
        $jugador2 = $posibilidades[rand(0, 2)];

        // Determinamos el ganador
        if ($jugador1 == "piedra" && $jugador2 == "tijera") {
            $ganador = 1;
        } elseif ($jugador1 == "tijera" && $jugador2 == "papel") {
            $ganador = 1;
        } elseif ($jugador1 == "papel" && $jugador2 == "piedra") {
            $ganador = 1;
        } elseif ($jugador1 == $jugador2) {
            $ganador = 0;
        } else {
            $ganador = 2;
        }

        return [$jugador1, $jugador2, $ganador];
    }

    // Función para mostrar el ganador
    function mostrarGanador($ganador)
    {
        if ($ganador == 1) {
            return 'Ha ganado jugador 1';
        } elseif ($ganador == 2) {
            return 'Ha ganado jugador 2';
        } else {
            return 'Empate';
        }
    }

    // Función para mostrar el icono correspondiente
    function mostrarIcono($jugada, $jugador)
    {
        if ($jugada == "piedra") {
            return $jugador == 1 ? PIEDRA1 : PIEDRA2;
        } elseif ($jugada == "papel") {
            return PAPEL;
        } else {
            return TIJERAS;
        }
    }

    // Jugar la partida y obtener los resultados
    list($jugador1, $jugador2, $ganador) = jugarJuego();

    ?>

    <h1>Piedra, papel, tijeras</h1>
    <p>Actualice la página para mostrar otra partida.</p>

    <div>
        <div style='display: flex; align-items: center; gap: 60px;'>
            <div style='text-align: center;'>
                <h2>Jugador 1</h2>
                <p style='font-size: 60px;'>
                    <?php echo mostrarIcono($jugador1, 1); ?>
                </p>
            </div>
            <div style='text-align: center;'>
                <h2>Jugador 2</h2>
                <p style='font-size: 60px;'>
                    <?php echo mostrarIcono($jugador2, 2); ?>
                </p>
            </div>
        </div>

        <strong>
            <?php echo mostrarGanador($ganador); ?>
        </strong>
    </div>

</body>

</html>