<?php
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted();

$resultado = (new ABMUsuario())->registrarUsuario($data);

echo $resultado;
?>