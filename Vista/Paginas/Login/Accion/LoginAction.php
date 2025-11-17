<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = data_submitted(); 
$respuesta = (new Session())->iniciarSesion($data);
 //var_dump($_SESSION);var_dump($data);
echo json_encode($respuesta);
?>