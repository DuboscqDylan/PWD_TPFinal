<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted();

$resultado = (new AbmUsuario())->registrarUsuario($data);

echo $resultado;
?>