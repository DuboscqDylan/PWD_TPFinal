<?php
include_once BASE.URL.'/configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new AbmCompra())->cambiarEstado($data);

echo json_encode($respuesta);
?>