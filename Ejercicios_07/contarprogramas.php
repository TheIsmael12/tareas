<!--
    
    Realizar un programa (contarprogramas.php) donde podamos indicar 
    un nombre de directorio y me muestre el nombre de los archivos con extensión .php, 
    indicándome cuantas líneas de código tiene cada fichero y el total de lineas del directorio.

-->

<?php

if (isset($_POST['directorio']) && !empty($_POST['directorio'])) {

    $directorio = $_POST['directorio'];
    $ficheros = scandir($directorio);

    $total = 0;

    foreach ($ficheros as $fichero) {

        $fichero = $directorio . '/' . $fichero;

        if (pathinfo($fichero, PATHINFO_EXTENSION) == 'php') {

            $lineas = count(file($fichero));
            $total += $lineas;

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar programas php</title>
</head>

<body>

    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="directorio">Directorio:</label>
        <br><br>
        <input type="text" name="directorio" id="directorio">
        <input type="submit" value="Enviar">
    </form>

    <br>
    <br>

    <?php

    if (isset($total) && !empty($total)) {

        echo "Los ficheros del directorio $directorio con extension .php son: <br><br>";
        
        foreach ($ficheros as $fichero) {
            
            if (pathinfo($fichero, PATHINFO_EXTENSION) == 'php') {
                
                $fichero = $directorio . '/' . $fichero;
                
                $lineas = count(file($fichero));
                echo "El fichero $fichero tiene $lineas lineas de codigo <br>";
                
            }
            
        }

        echo "<br>";

        echo "El total de lineas del directorio es $total lineas de codigo.";

    } else {

        echo "No se ha indicado el directorio";

    }

    ?>

</body>

</html>