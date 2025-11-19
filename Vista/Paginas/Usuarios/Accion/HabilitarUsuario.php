<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = data_submitted();  // Obtener los datos enviados
var_dump($_POST);
$respuesta = (new AbmUsuario())->habilitarUsuario($data);

echo json_encode($respuesta);
