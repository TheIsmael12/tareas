<?php

// Inicia la sesión si no está iniciada

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}

// Si no hay un cliente en la sesión, se redirige a la bienvenida y al rellenar el formulario asigna cliente y crea un pedido vacio.

if (isset($_GET['cliente']) && $_GET['cliente'] != "" && !isset($_SESSION['cliente'])) {

    $_SESSION['cliente'] = $_GET['cliente'];
    $_SESSION['pedidos'] = [];

}

if (!isset($_SESSION['cliente'])) {

    require_once('bienvenida.php');

}

// Cargar compra:

if (isset($_SESSION['cliente'])) {

    function htmlTablaPedidos()
    {
        $msg = "";

        if (isset($_SESSION['pedidos']) && count($_SESSION['pedidos']) > 0) {

            $msg .= "Este es su pedido:";
            $msg .= "<br><br>";
            $msg .= "<table style='border: 1px solid black;'>";

            foreach ($_SESSION['pedidos'] as $pedido) {

                $msg .= "<tr>
                            <td>" . htmlspecialchars($pedido['fruta']) . "</td>
                            <td>" . htmlspecialchars($pedido['cantidad']) . "</td>
                        </tr>";

            }

            $msg .= "</table>";

        } else {

            $msg .= "No tiene pedidos registrados.";

        }

        return $msg;

    }

    if (isset($_POST['accion']) && $_POST['accion'] == "Terminar") {

        $compraRealizada = htmlTablaPedidos();
        require_once('despedida.php');
        session_destroy();
        exit();

    } else {

        // Anotar acciones:

        if (isset($_POST['accion'])) {

            $accion = $_POST['accion'];

            if ($accion === "Anotar" && isset($_POST['fruta']) && isset($_POST['cantidad'])) {

                $fruta = $_POST['fruta'];
                $cantidad = $_POST['cantidad'];

                if ($cantidad > 0) {

                    $frutaExistente = false;

                    // Verificar si la fruta ya está en los pedidos

                    foreach ($_SESSION['pedidos'] as &$pedido) {
                        if ($pedido['fruta'] === $fruta) {
                            $pedido['cantidad'] += $cantidad;
                            $frutaExistente = true;
                            break;
                        }
                    }

                    unset($pedido);

                    // Si la fruta no estaba en el pedido, se añade como nuevo pedido

                    if (!$frutaExistente) {

                        $_SESSION['pedidos'][] = [
                            'fruta' => $fruta,
                            'cantidad' => $cantidad
                        ];

                    }

                }

            }

        }

        $compraRealizada = htmlTablaPedidos();
        require_once('compra.php');

    }

}