<div>
    <b> Detalles:</b><br>

    <table>

        <tr>
            <td>Longitud: </td>
            <td><?= strlen(trim($_REQUEST['comentario'])) ?></td>
        </tr>

        <tr>
            <td>Nº de palabras: </td>
            <td><?= str_word_count(trim($_REQUEST['comentario'])) ?></td>
        </tr>

        <tr>
            <td>Letra + repetida: </td>
            <td><?= letraMasRepetida(trim($_REQUEST['comentario'])) ?></td>
        </tr>

        <tr>
            <td>Palabra + repetida:</td>
            <td><?= palabraMasRepetida(trim($_REQUEST['comentario'])) ?></td>
        </tr>

    </table>

</div>