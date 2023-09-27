<?php

// Incluir el archivo de configuración del sitio.
require_once("../config.php");

// Definir una clase llamada UrlShortener para gestionar las URL cortas.
class UrlShortener
{
    protected $db;

    // Constructor de la clase. Establece la conexión a la base de datos al crear una instancia.
    public function __construct()
    {
        $this->db = new mysqli(HOST_NAME, USER_NAME, USER_PASSWORD, DB_NAME);

        // Verificar si hubo un error al conectar a la base de datos y redirigir a la página de inicio en caso de error.
        if ($this->db->connect_errno) {
            header("Location: ../index.php?error=db");
            die(); // Terminar la ejecución del script.
        }
    }

    /**
     * Función para generar un código único y aleatorio para las nuevas URLs.
     *
     * @param string $idOfRow Número de fila de la URL guardada en la base de datos.
     *
     * @return integer
     */

    public function generateUniqueCode($idOfRow)
    {
        $idOfRow += 10000000;
        return base_convert($idOfRow, 10, 36);
    }

    /**
     * Valida una URL, comprueba si ya está presente en la base de datos y la inserta en la base de datos si es válida y nueva.
     *
     * @param string $orignalURL URL original.
     *
     * @return string
     */

    public function validateUrlAndReturnCode($orignalURL)
    {
        $orignalURL = trim($orignalURL);

        // Comprueba si la URL es válida utilizando la función filter_var.
        if (!filter_var($orignalURL, FILTER_VALIDATE_URL)) {
            header("Location: ../index.php?error=inurl");
            die(); // Terminar la ejecución del script si la URL no es válida.
        } else {
            // Escapar la URL para evitar ataques SQL.
            $orignalURL      = $this->db->real_escape_string($orignalURL);
            // Consulta para verificar si la URL ya existe en la base de datos.
            $existInDatabase = $this->db->query("SELECT * FROM link WHERE url ='{$orignalURL}'");

            if ($existInDatabase->num_rows) {
                $uniqueCode = $existInDatabase->fetch_object()->code;

                return $uniqueCode;
            }

            // Si la URL no existe en la base de datos, se inserta y se le asigna un código único.
            $insertInDatabase  = $this->db->query("INSERT INTO link (url,created) VALUES ('{$orignalURL}',NOW())");
            $fetchFromDatabase = $this->db->query("SELECT * FROM link WHERE url = '{$orignalURL}'");
            $getIdOfRow        = $fetchFromDatabase->fetch_object()->id;
            $uniqueCode        = $this->generateUniqueCode($getIdOfRow);
            $updateInDatabase  = $this->db->query("UPDATE link SET code = '{$uniqueCode}' WHERE url = '{$orignalURL}'");

            return $uniqueCode;
        }
    }

    /**
     * Inserta la URL y el código personalizado en la base de datos.
     *
     * @param string $orignalURL URL original.
     * @param string $customUniqueCode Código personalizado deseado.
     *
     * @return boolean
     */

    public function returnCustomCode($orignalURL, $customUniqueCode)
    {
        $orignalURL       = trim($orignalURL);
        $customUniqueCode = trim($customUniqueCode);

        if (filter_var($orignalURL, FILTER_VALIDATE_URL)) {
            // Inserta la URL y el código personalizado en la base de datos.
            $insert = $this->db->query("INSERT INTO link (url,code,created) VALUES ('{$orignalURL}','{$customUniqueCode}',NOW())");

            return true;
        }

        return false;
    }

    /**
     * Obtiene la URL original basada en la URL corta.
     *
     * @param string $string URL corta.
     *
     * @return string
     */

    public function getOrignalURL($string)
    {
        // Escapar la cadena para evitar ataques SQL.
        $string = $this->db->real_escape_string(strip_tags(addslashes($string)));
        // Consulta para obtener la URL original en función de la URL corta.
        $rows   = $this->db->query("SELECT url FROM link WHERE code = '{$string}'");

        if ($rows->num_rows) {
            return $rows->fetch_object()->url;
        } else {
            header("Location: index.php?error=dnp");
            die(); // Terminar la ejecución del script si la URL corta no existe en la base de datos.
        }
    }

    /**
     * Comprueba si el código de URL corta ya está presente en la base de datos.
     *
     * @param string $customCode Código personalizado.
     *
     * @return boolean
     */

    public function checkUrlExistInDatabase($customCode)
    {
        $customCode  = $this->db->real_escape_string(strip_tags(addslashes($customCode)));
        // Consulta para verificar si el código de URL corta ya existe en la base de datos.
        $fetchedRows = $this->db->query("SELECT url FROM link WHERE code = '{$customCode}' LIMIT 1");

        return $fetchedRows->num_rows > 0;
    }

    /**
     * Genera una etiqueta de enlace para la nueva URL corta.
     *
     * @param string $uniqueCode Código único de la URL corta.
     *
     * @return string
     */

    public function generateLinkForShortURL($uniqueCode = '')
    {
        return '<a href="' . BASE_URL . $uniqueCode . '" target="_blank">' . BASE_URL . $uniqueCode . '</a>';
    }
}
