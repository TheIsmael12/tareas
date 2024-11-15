<!-- 

Realizar un programa (verdirinfo.php) donde podamos indicar un nombre de directorio y 
me muestre los archivos que lo componen indicando el nombre, el tipo de archivo (MIME) 
y su tamaño en bytes. Mostrar la lista ordenada por tamaño.

-->

<?php

if (isset($_POST['directorio']) && !empty($_POST['directorio'])) {

    $directorio = $_POST['directorio'];
    $ficheros = scandir($directorio);
    $lista = array();

    foreach ($ficheros as $fichero) {

        if ($fichero != "." && $fichero != "..") {

            $ruta = $directorio . '/' . $fichero;
            $tipo = mime_content_type($ruta);
            $tamanio = filesize($ruta);
            $lista[] = array("nombre" => $fichero, "tipo" => $tipo, "tamanio" => $tamanio);

        }

    }

    usort($lista, function ($a, $b) {

        return $a["tamanio"] - $b["tamanio"];

    });

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

    <h1>Ver Info de directorio: </h1>

    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="directorio">Directorio:</label>
        <input type="text" id="directorio" name="directorio" />
    </form>

    <?php

    if (isset($lista) && !empty($lista)) {

        echo "<h2>Archivos en el directorio: " . $directorio . "</h2>";

        foreach ($lista as $fichero) {

            echo $fichero["nombre"] . " | " . $fichero["tipo"] . " | " . $fichero["tamanio"] . " bytes<br>";

        }

    }else{

        echo "<h2>El directorio no existe o no tiene archivos</h2>";

    }   

    ?>

</body>

</html>