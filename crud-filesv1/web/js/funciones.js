/**
 * Funciones auxiliares de javascripts 
 */
function confirmarBorrar(nombre, id) {
  if (confirm("¿Quieres eliminar el usuario:  " + nombre + "?")) {
    document.location.href = "?orden=Borrar&id=" + id;
  }
}

/**
 *  Muestra la clave del formulario, cambia de password a text
 */
function mostrarclave() {

  clave_id = document.getElementById("clave_id");
  clave_id.type = (clave_id.type == "text") ? "password" : "XXXX";

}

/**
 *  Pide confirmación de volcar los datos 
 */
function confirmarVolcar() {

  if (confirm("¿Quieres volcar los datos en la base de datos?")) {

    document.location.href = "?orden=Terminar";

  }

}