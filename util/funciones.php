<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
function data_submitted()
{

    $_AAux = array();
    if (!empty($_REQUEST))
        $_AAux = $_REQUEST;
    // if (count($_AAux)){
    //  foreach ($_AAux as $indice => $valor) {
    //     if ($valor=="")
    //         $_AAux[$indice] = 'null' ;
    // }
    //  }
    return $_AAux;
}
function verEstructura($e)
{
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

spl_autoload_register(function ($class_name) {
    $directorys = array(
        ROOT_PATH . 'Modelo/',
        ROOT_PATH . 'Modelo/conector/',
        ROOT_PATH . 'Control/',
    );
    foreach ($directorys as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            require_once($directory . $class_name . '.php');
            return;
        }
    }
});
