<!-- 

Realizar un programa (verfichero.php) donde podamos indicar un fichero de texto plano 
( .txt, html o php por ejemplo) y que me lo muestre por pantalla, informando del número 
 de caracteres y del número de líneas que contiene.

-->

<?php

if (isset($_POST["fichero"]) && $_POST["fichero"] != "") {

    $fichero = $_POST["fichero"];
    $fichero = fopen($fichero, "r");
    $caracteres = 0;
    $lineas = 0;

    while (!feof($fichero)) {

        $linea = fgets($fichero);
        $caracteres += strlen($linea);
        $lineas++;

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1>Ver fichero y caracteristicas:</h1>

    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="fichero">Inserta fichero de texto plano.</label>
        <br><br>
        <input type="file" name="fichero" id="fichero">
        <button type="submit">Enviar</button>
    </form>

    <br><br>

    <?php

    if (isset($fichero)) {

        echo "El fichero tiene " . $caracteres . " caracteres y " . $lineas . " lineas.";

        $fichero;

    } else{
        echo "No se ha enviado el fichero.";
    }

    ?>

</body>

</html>