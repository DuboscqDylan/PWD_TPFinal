<?php 
// Configs de aplicación. Incluir en toda pagina del proyecto

// Browser config
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

// Paths constants
$dir = '/PWD_TPFinal'; // Directorio del proyecto dentro del servidor (default: dentro de "C:/xampp/htdocs")
define('DOMAIN_URL', 'http://'.$_SERVER['HTTP_HOST']); // URL del domino
define('CURRENT_URL', DOMAIN_URL.$_SERVER['REQUEST_URI']); //URL Actual
define('BASE_URL', DOMAIN_URL.$dir ); // URL principal del sitio (direccion web)
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].$dir); // Raiz del proyecto (directorio local)
define('STRUCTURE_PATH', ROOT_PATH.'/Vista/Estructura'); // Directorio de la estructura (Header, footer, menu, etc)
//define('INCLUDE_PATH', STRUCTURE_PATH.'/Include'); // Directorio de la estructura de scripts a utilizar
$_SERVER["ROOT"] = ROOT_PATH; //Establece directorio raiz en array

// Global includes
include_once(ROOT_PATH.'/Util/Autoloader.php'); // Funciones usadas en toda la sesion
include_once(ROOT_PATH.'/Util/funciones.php');
include_once(ROOT_PATH.'/Control/Session.php');
$sesion = new Session();
require_once 'vendor/autoload.php'; // Con Composer
?>
