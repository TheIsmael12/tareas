<?php

/*
 * Acceso a datos con BD Usuarios : 
 * Usando la librería PDO *******************
 * Uso el Patrón Singleton :Un único objeto para la clase
 * Constructor privado, y métodos estáticos 
 */

class AccesoDatos
{

    private static $modelo = null;
    private $dbh = null;

    public static function getModelo()
    {
        if (self::$modelo == null) {
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }

    // Constructor privado  Patron singleton

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DATABASE . ";charset=utf8";
            $this->dbh = new PDO($dsn, DB_USER, DB_PASSWD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión " . $e->getMessage();
            exit();
        }
    }

    // Cierro la conexión anulando todos los objectos relacioanado con la conexión PDO (stmt)

    public static function closeModelo()
    {
        if (self::$modelo != null) {
            $obj = self::$modelo;
            // Cierro la base de datos
            $obj->dbh = null;
            self::$modelo = null; // Borro el objeto.
        }
    }

    // Devuelvo cuantos filas tiene la tabla

    public function numClientes(): int
    {
        $result = $this->dbh->query("SELECT id FROM clientes");
        $num = $result->rowCount();
        return $num;
    }


    // SELECT Devuelvo la lista de Usuarios

    public function getClientes($offset, $limit): array
    {
        $tuser = [];

        // Prepara la consulta SQL con LIMIT para paginación

        $stmt_usuarios = $this->dbh->prepare("SELECT * FROM clientes LIMIT :offset, :limit");

        // Asocia los parámetros para evitar inyecciones SQL

        $stmt_usuarios->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt_usuarios->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        // Configura el modo de obtención de datos

        $stmt_usuarios->setFetchMode(PDO::FETCH_CLASS, 'cliente');

        // Ejecuta la consulta y almacena los resultados

        if ($stmt_usuarios->execute()) {

            while ($user = $stmt_usuarios->fetch()) {

                $tuser[] = $user;
            }
        }

        // Devuelvo el array de objetos

        return $tuser;
    }


    // SELECT Devuelvo un usuario o false

    public function getCliente(int $id)
    {
        try {

            // Obtener el cliente desde la base de datos
            $stmt_cli = $this->dbh->prepare("SELECT * FROM clientes WHERE id = :id");
            $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
            $stmt_cli->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_cli->execute();

            $cli = $stmt_cli->fetch();

            if ($stmt_cli->execute()) {

                if ($obj = $stmt_cli->fetch()) {

                    $cli = $obj;

                    $response = @file_get_contents("http://ip-api.com/json/" . $cli->ip_address);
                    $data = $response ? json_decode($response, true) : null;

                    $cli->lat = $data['lat'] ?? null;
                    $cli->long = $data['lon'] ?? null;
                } else {

                    header("Location: ?");
                }
            }

            return $cli;
        } catch (Exception $e) {

            error_log("Error en getCliente: " . $e->getMessage());
            return null;
        }
    }

    // INSERT

    public function addCliente($cli): bool
    {
        try {

            // Insertar datos en la base de datos
            $query = "INSERT INTO Clientes (first_name, last_name, email, gender, ip_address, telefono) 
                  VALUES (:first_name, :last_name, :email, :gender, :ip_address, :telefono)";

            $stmt = $this->dbh->prepare($query);

            $stmt->bindParam(':first_name', $cli->first_name);
            $stmt->bindParam(':last_name', $cli->last_name);
            $stmt->bindParam(':email', $cli->email);
            $stmt->bindParam(':gender', $cli->gender);
            $stmt->bindParam(':ip_address', $cli->ip_address);
            $stmt->bindParam(':telefono', $cli->telefono);

            $stmt->execute();

            if ($stmt->rowCount() === 1) {

                // Obtener la ID del registro insertado
                $lastInsertId = $this->dbh->lastInsertId();
                // Manejar la carga del archivo
                $this->handleFileUpload($cli->file, $lastInsertId);

                return (int)$lastInsertId;
            }

            return false;
        } catch (Exception $e) {

            error_log($e->getMessage());
            return false;
        }
    }

    // UPDATE

    public function modCliente($cli): bool
    {
        try {

            $this->handleFileUpload($cli->file, $cli->id);

            $query = "UPDATE Clientes 
                      SET first_name = :first_name, last_name = :last_name, email = :email, 
                          gender = :gender, ip_address = :ip_address, telefono = :telefono 
                      WHERE id = :id";

            $stmt = $this->dbh->prepare($query);

            $stmt->bindParam(':first_name', $cli->first_name);
            $stmt->bindParam(':last_name', $cli->last_name);
            $stmt->bindParam(':email', $cli->email);
            $stmt->bindParam(':gender', $cli->gender);
            $stmt->bindParam(':ip_address', $cli->ip_address);
            $stmt->bindParam(':telefono', $cli->telefono);
            $stmt->bindParam(':id', $cli->id);

            $stmt->execute();

            if ($stmt->rowCount() === 1) {

                // Manejar la carga del archivo
                $this->handleFileUpload($cli->file, $cli->id);
                return true;
            }

            return false;
        } catch (Exception $e) {

            error_log($e->getMessage());
            return false;
        }
    }

    //DELETE

    public function borrarCliente(int $id): bool
    {

        $stmt_boruser   = $this->dbh->prepare("delete from clientes where id =:id");

        $stmt_boruser->bindValue(':id', $id);
        $stmt_boruser->execute();
        $resu = ($stmt_boruser->rowCount() == 1);
        return $resu;
    }

    // Subir archivo

    private function handleFileUpload($file, $id): bool
    {
        // Comprobamos si el archivo existe y es una carga válida
        if ($file && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {

            // Tipos MIME permitidos y tamaño máximo en bytes
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxFileSize = 50000 * 1024; // 500 KB

            // Detectamos el tipo MIME del archivo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            // Obtenemos el tamaño del archivo
            $fileSize = filesize($file['tmp_name']);

            // Comprobamos si el tipo MIME es válido
            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                throw new Exception("El archivo debe ser una imagen JPG o PNG.");
            }

            // Comprobamos si el tamaño del archivo es válido
            if ($fileSize > $maxFileSize) {
                throw new Exception("El archivo debe tener un tamaño inferior a 500 KB.");
            }

            // Definir el directorio de carga y crear el nombre del archivo
            $uploadDir = __DIR__ . '/../uploads/';

            // Crear el nombre del archivo usando el ID o un identificador único
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

            $fileName = sprintf('%08d', $id) . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;

            // Verificamos si el archivo se movió correctamente
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {

                throw new Exception("Error al subir la imagen.");
            }

            // Retornamos el nombre del archivo cargado
            return true;
        }

        // Si no se carga ningún archivo
        return false;
    }

    // Obtener el siguiente cliente en función del ID

    public function getClienteSiguiente($idActual)

    {
        // Consulta para obtener el siguiente cliente con un ID mayor al actual
        $query = "SELECT * FROM clientes WHERE id > ? ORDER BY id ASC LIMIT 1";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute([$idActual]);

        // Verificamos si encontramos un siguiente cliente
        $cliente = $stmt->fetch(PDO::FETCH_OBJ);

        return $cliente ?: null; // Devuelve el cliente o null si no existe

    }

    // Obtener el cliente anterior en función del ID

    public function getClienteAnterior($idActual)
    {

        // Consulta para obtener el cliente anterior con un ID menor al actual
        $query = "SELECT * FROM clientes WHERE id < ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute([$idActual]);

        // Verificamos si encontramos un cliente anterior
        $cliente = $stmt->fetch(PDO::FETCH_OBJ);

        return $cliente ?: null; // Devuelve el cliente o null si no existe

    }

    // Validar email

    public function validarEmail($email, $id)
    {
        // Consulta SQL para contar los registros con el email
        $query = "SELECT COUNT(*) FROM Clientes WHERE email = :email";

        // Si se pasa un ID, excluimos ese registro
        if ($id !== null) {
            $query .= " AND id != :id";
        }

        // Preparar y ejecutar la consulta
        $stmt_emailUnique = $this->dbh->prepare($query);
        $stmt_emailUnique->bindParam(':email', $email);

        if ($id !== null) {
            $stmt_emailUnique->bindParam(':id', $id);
        }

        $stmt_emailUnique->execute();

        // Obtener el resultado de la consulta
        $count = $stmt_emailUnique->fetchColumn();

        return $count;  // Retorna el número de coincidencias

    }

    // Evito que se pueda clonar el objeto. (SINGLETON)

    public function __clone()
    {
        trigger_error('La clonación no permitida', E_USER_ERROR);
    }
}
