<?php
include_once BASE_URL.'/configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new AbmProducto())->listarProductos();

echo json_encode($respuesta);
?>