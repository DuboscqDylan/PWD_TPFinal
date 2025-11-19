<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$resultado = (new AbmUsuario())->registrarUsuario($data);

echo $resultado;
