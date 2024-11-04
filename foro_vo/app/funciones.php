<?php
function usuarioOk($usuario, $contraseña): bool
{

   if (strlen($usuario) < 8) {

      return false;

   } elseif ($contraseña != strrev($usuario)) {

      return false;

   }

   return true;

}

function palabraMasRepetida($texto) {

   $texto = strtolower($texto);
   $palabras = str_word_count($texto, 1);
   $frecuencia = array_count_values($palabras);

   $palabraMasRepetida = '';
   $maxFrecuencia = 0;

   foreach ($frecuencia as $palabra => $cantidad) {

       if ($cantidad > $maxFrecuencia) {

           $palabraMasRepetida = $palabra;
           $maxFrecuencia = $cantidad;

       }

   }

   return $palabraMasRepetida;

}

function letraMasRepetida($texto) {

   $texto = strtolower($texto);
   $texto = preg_replace('/[^a-z]/', '', $texto);
   $frecuencia = count_chars($texto, 1);

   $letraMasRepetida = '';
   $maxFrecuencia = 0;

   foreach ($frecuencia as $codigoAscii => $cantidad) {

       if ($cantidad > $maxFrecuencia) {

           $letraMasRepetida = chr($codigoAscii);
           $maxFrecuencia = $cantidad;

       }

   }

   return $letraMasRepetida;
   
}