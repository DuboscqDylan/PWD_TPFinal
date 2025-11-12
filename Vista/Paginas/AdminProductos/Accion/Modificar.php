<?php
include_once $_SERVER['DOCUMENT_ROOT']."/configuracion.php";
$data = Funciones::data_submitted();  // Obtener los datos enviados

$respuesta = (new AbmProducto())->Modificar($data);

echo json_encode($respuesta);
?>
