<?php

session_start();

require_once 'app/helpers/util.php';
require_once 'app/helpers/validator.php';
require_once 'app/config/configDB.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/AccesoDatos.php';
require_once 'app/controllers/crudclientes.php';

//---- PAGINACIÓN ----

define('FPAG', 10); // Número de filas por página

$midb = AccesoDatos::getModelo();
$totalfilas = $midb->numClientes();
$totalPaginas = ceil($totalfilas / FPAG);

if (!isset($_SESSION['pagina'])) {
    $_SESSION['pagina'] = 1;
}

// Inicialización de la página actual
$pagina = isset($_SESSION['pagina']) ? $_SESSION['pagina'] : 1;  // Si no está definida, iniciamos en la página 1

$_SESSION['msg'] = "";

// Buffer de salida

ob_start();

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // Proceso las órdenes de CRUD clientes
    if (isset($_GET['orden'])) {

        switch ($_GET['orden']) {

            case "Nuevo":
                crudAlta();
                break;
            case "Borrar":
                if (isset($_GET['id'])) crudBorrar($_GET['id']);
                break;
            case "Modificar":
                if (isset($_GET['id'])) crudModificar($_GET['id']);
                break;
            case "Detalles":
                if (isset($_GET['id'])) crudDetalles($_GET['id']);
                break;
            case "GenerarPDF":
                if (isset($_GET['id'])) crudGenerarPDF($_GET['id']);
                break;
            case "Terminar":
                crudTerminar();
                break;
                
        }

    }

} else {

    // POST: Formulario de alta o modificación

    if (isset($_POST['orden'])) {

        switch ($_POST['orden']) {

            case "Nuevo":
                crudPostAlta();
                break;
            case "Modificar":
                crudPostModificar();
                break;
        }

    }

}

if (isset($_GET['pag'])) {

    $_SESSION['pagina'] = $_GET['pag']; // Actualizamos el valor de la página con el parámetro GET

}

// Ajuste de la paginación y cálculo de $offset
$pagina = $_SESSION['pagina'];
$offset = FPAG * ($pagina - 1);
$limit = FPAG;

// Si no hay nada en el buffer, genero la vista por defecto

if (ob_get_length() == 0) {

    $db = AccesoDatos::getModelo();
    $tclientes = $db->getClientes($offset, $limit);
    require_once "app/views/list.php";
}

$contenido = ob_get_clean();
$msg = $_SESSION['msg'];
require_once "app/views/principal.php";