<?php
include_once BASE_URL.'/configuracion.php';
$data = Funciones::data_submitted();

$resultado = (new AbmUsuario())->registrarUsuario($data);

echo $resultado;
?>