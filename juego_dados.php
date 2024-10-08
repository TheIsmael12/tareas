<!--- Realizar el juego de dados --->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinco Dados</title>
</head>

<body>

    <?php

    // Definir los símbolos de los dados

    $dados = [
        1 => "&#9856;",
        2 => "&#9857;",
        3 => "&#9858;",
        4 => "&#9859;",
        5 => "&#9860;",
        6 => "&#9861;"  
    ];

    // Inicialización de las variables
    $jugador1 = 0;
    $jugador2 = 0;

    $jugador1Tiradas = [];
    $jugador2Tiradas = [];

    // Función que maneja el juego y devuelve las jugadas y el ganador
    function jugarJuego(){
        global $jugador1, $jugador2, $jugador1Tiradas, $jugador2Tiradas;

        $posibilidades = [1, 2, 3, 4, 5, 6];

        // Jugada aleatoria para ambos jugadores

        for ($i = 0; $i < 5; $i++) {

            $tiradaJ1 = $posibilidades[rand(0, 5)];
            $tiradaJ2 = $posibilidades[rand(0, 5)];

            $jugador1 += $tiradaJ1;
            $jugador2 += $tiradaJ2;

            $jugador1Tiradas[] = $tiradaJ1;
            $jugador2Tiradas[] = $tiradaJ2;
        }

        // Determinamos el ganador
        if ($jugador1 == $jugador2) {
            $ganador = 0;
        } elseif ($jugador1 > $jugador2) {
            $ganador = 1;
        } else {
            $ganador = 2;
        }

        return [$jugador1, $jugador2, $ganador];
    }

    // Función para mostrar el ganador
    function mostrarGanador($ganador){
        if ($ganador == 1) {
            return 'Ha ganado Jugador 1';
        } elseif ($ganador == 2) {
            return 'Ha ganado Jugador 2';
        } else {
            return 'Empate';
        }
    }

    // Función para mostrar el icono correspondiente a las tiradas
    function mostrarTiradas($jugador){
        global $jugador1Tiradas, $jugador2Tiradas, $dados;

        $tiradas = ($jugador == 1) ? $jugador1Tiradas : $jugador2Tiradas;

        // Mostrar las tiradas

        foreach ($tiradas as $tirada) {
            echo $dados[$tirada] . " ";
        }
    }

    // Jugar la partida y obtener los resultados
    
    list($jugador1, $jugador2, $ganador) = jugarJuego();

    ?>

    <h1>Cinco Dados</h1>
    <p>Actualice la página para mostrar una nueva tirada.</p>

    <div>
        <div style='display: block; align-items: center; gap: 60px;'>
            <div style='text-align: center; display: flex; gap: 40px; align-items: center;'>
                <h2>Jugador 1</h2>
                <p style='font-size: 40px; background-color: red; padding: 10px;'>
                    <?php echo mostrarTiradas(1); ?>
                </p>
                <p>
                    <?php echo $jugador1; ?> puntos
                </p>
            </div>
            <div style='text-align: center; display: flex; gap: 40px; align-items: center;'>
                <h2>Jugador 2</h2>
                <p style='font-size: 40px; background-color: blue; padding: 10px;'>
                    <?php echo mostrarTiradas(2); ?>
                </p>
                <p>
                    <?php echo $jugador2; ?> puntos
                </p>
            </div>
        </div>

        <strong>
            <?php echo mostrarGanador($ganador); ?>
        </strong>
    </div>

</body>

</html>