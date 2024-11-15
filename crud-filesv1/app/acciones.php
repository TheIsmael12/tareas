<?php

// Borra el elemento indicado de tabla de usuarios
// Reordena indexa la tabla

function accionBorrar($id)
{
    // anotar en $_SESSION['msg'] un mensaje si el usuario ha sido eliminado o si no existe

    if (!isset($_SESSION['tuser'][$id])) {

        $_SESSION['msg'] = "  El usuario con login $id no existe";

    } else {

        // Elimino de la tabla

        unset($_SESSION['tuser'][$id]);
        $_SESSION['msg'] = "  Usuario con login $id eliminado";

    }

}

// Termina: Cierra sesión y vuelva los datos.

function accionTerminar()
{

    volcarDatos($_SESSION['tuser']);
    session_destroy();
    $_SESSION['msg'] = "  Todos datos se han guardado ";

}

// Muestra un formularios con los datos de un usuario de la posición $id de la tabla.

function accionDetalles($id)
{

    $login = $id;
    $usuario = $_SESSION['tuser'][$id];
    $clave = $usuario[0];
    $nombre = $usuario[1];
    $comentario = $usuario[2];
    $orden = "Detalles";
    include_once "layout/formulario.php";
    exit();

}

// Muestra  el formularios con los datos permitiendo la modificación.

function accionModificar($id)
{

    $login = $id;
    $usuario = $_SESSION['tuser'][$id];
    $clave = $usuario[0];
    $nombre = $usuario[1];
    $comentario = $usuario[2];
    $orden = "Modificar";
    include_once "layout/formulario.php";
    exit();

}

// Modifica el contenido de usuario.

function accionPostModificar()
{

    $id = $_POST['login'];
    $nuevovalor = [$_POST['clave'], $_POST['nombre'], $_POST['comentario']];
    $_SESSION['tuser'][$id] = $nuevovalor;
    $_SESSION['msg'] = " Usuario con login $id actualizado";

}

// Muestra  el formulario con los datos vacios para realizar una alta.

function accionAlta()
{

    $nombre = "";
    $login = "";
    $clave = "";
    $comentario = "";
    $orden = "Nuevo";
    include_once "layout/formulario.php";
    exit();

}

// Proceso los datos del formularios guardándolo en la sesión
// Debe evitar que se puedan introducir dos usuarios con el mismo login y
// que exista algún campo vacio.

function accionPostAlta()
{

    limpiarArrayEntrada($_POST); //Evito la posible inyección de código

    if (isset($_SESSION['tuser'][$_POST['login']])) {

        $_SESSION['msg'] = "  El usuario con login " . $_POST['login'] . " ya existe";

    } else {

        if (empty($_POST['login']) || empty($_POST['clave']) || empty($_POST['nombre']) || empty($_POST['comentario'])) {

            $_SESSION['msg'] = "  Debe rellenar todos los campos";

        } else {

            $nuevo = [$_POST['clave'], $_POST['nombre'], $_POST['comentario']];
            $_SESSION['tuser'][$_POST['login']] = $nuevo;
            $_SESSION['msg'] = "  Nuevo usuario añadido.";

        }

    }

}