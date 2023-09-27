<?php

// Nombre del sitio

if (!defined('SITE_NAME')) {
    define('SITE_NAME', "Acortador de Enlaces en PHP y MySQL");
}

// URL, debe tener el slash al final */*

if (!defined('BASE_URL')) {
    define('BASE_URL', "http://localhost/acortador/");
}

// Configuraciones de la base de datos

if (!defined('HOST_NAME')) {
    define('HOST_NAME', "127.0.0.1:3306");
}

if (!defined('DB_NAME')) {
    define('DB_NAME', "acortador");
}

if (!defined('USER_NAME')) {
    define('USER_NAME', "root");
}

if (!defined('USER_PASSWORD')) {
    define('USER_PASSWORD', "");
}
