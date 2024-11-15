<!-- 
 
Realizar programa php (contador.php) que muestre cuantas veces se ha accedido a la página en total 
y cuantas  veces desde un mismo navegador trabajando sobre un fichero llamado accesos.txt y con un 
valor de cookie válido por tres meses.

-->

<?php

if (isset($_COOKIE['contador'])) {

    $contador = $_COOKIE['contador'];
    $contador++;
    setcookie('contador', $contador, time() + 60 * 60 * 24 * 30 * 3);
    $fichero = fopen('accesos.txt', 'a');
    fwrite($fichero, "Acceso desde el navegador " . $_SERVER['HTTP_USER_AGENT'] . " en la fecha " . date('d-m-Y H:i:s') . "\n");
    fclose($fichero);

} else {

    $contador = 1;
    setcookie('contador', $contador, time() + 60 * 60 * 24 * 30 * 3);
    $fichero = fopen('accesos.txt', 'a');
    fwrite($fichero, "Acceso desde el navegador " . $_SERVER['HTTP_USER_AGENT'] . " en la fecha " . date('d-m-Y H:i:s') . "\n");
    fclose($fichero);

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

    <p>Has accedido a esta página <?php echo $contador; ?> veces</p>

</body>

</html>