<?php

// Verificar si la petición es por método POST.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Función para evitar inyección de código en nombre y alias.

    function limpiarDato($dato)
    {
        $dato = trim($dato);                  // Elimina espacios innecesarios.
        $dato = stripslashes($dato);          // Elimina barras invertidas.
        $dato = htmlspecialchars($dato);      // Convierte caracteres especiales en entidades HTML.
        return $dato;
    }

    // 2. Procesar los datos del formulario.

    $nombre = limpiarDato($_POST['name']);
    $alias = limpiarDato($_POST['alias']);
    $edad = (int) $_POST['edad'];
    $guns = isset($_POST['guns']) ? $_POST['guns'] : [];
    $practicaMagia = $_POST['magic'] === 'yes' ? 'Sí' : 'No';

    // 3. Validar y procesar la subida de la imagen.

    $imagenError = "";
    $rutaImagen = 'uploads/calavera.png'; // Imagen por defecto en caso de error.

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $archivo = $_FILES['image'];
        $tipoArchivo = $archivo['type'];
        $tamañoArchivo = $archivo['size'];

        // Solo aceptar archivos PNG de hasta 10 KB.

        if ($tipoArchivo === 'image/png' && $tamañoArchivo <= 100240) {

            // Mover el archivo a la carpeta uploads.

            $nombreArchivo = uniqid() . '.png';
            $rutaDestino = 'uploads/' . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {

                $rutaImagen = $rutaDestino;

            } else {

                $imagenError = "Error al mover la imagen en la carpeta.";  // Error al subir la imagen.
            }

        } else {

            $imagenError = "El tamaño de la imagen es muy grande o el tipo de archivo no es PNG.";  // Tipo o tamaño incorrecto.
        }

    }

    // 4. Mostrar los datos procesados.

    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Datos del Jugador</title>
    </head>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .datos {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: start;
        }

        .datos div {
            margin: 0 20px;
        }
    </style>

    </head>

    <body>
        <h1>Datos del Jugador</h1>
        <div class="datos">
            <div>
                <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
                <p><strong>Alias:</strong> <?php echo $alias; ?></p>
                <p><strong>Edad:</strong> <?php echo $edad; ?></p>
                <p><strong>Armas selecionadas:</strong> <?php echo implode(", ", $guns); ?></p>
                <p><strong>¿Practica artes mágicas?:</strong> <?php echo $practicaMagia; ?></p>
            </div>
            <div>
                <h3>Imagen subida: </h3>
                <?php if ($imagenError != ""): ?>
                    <p><strong>Error:</strong> <?php echo $imagenError; ?> </p>
                <?php endif; ?>
                <img src="<?php echo $rutaImagen; ?>" alt="Imagen del Jugador" width="200">
            </div>
        </div>
        <a href="index.php">Volver al formulario</a>
    </body>

    </html>

    <?php

} else {

    // En caso de metodo GET mostramos el formulario.

    include("captura.html");

}

?>