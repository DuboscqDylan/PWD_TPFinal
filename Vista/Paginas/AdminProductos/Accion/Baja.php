<?php
include_once $_SERVER['DOCUMENT_ROOT']."/configuracion.php";

$data = Funciones::data_submitted();

$respuesta = (new AbmProducto())->Baja($data);

echo json_encode($respuesta);
?>
