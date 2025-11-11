<?php
include_once BASE_URL.'/configuracion.php';
$data = Funciones::data_submitted();

$respuesta = (new AbmProducto())->listarProductos($data); //Obtiene array unitario con producto idproducto en $data

echo json_encode($respuesta);
?>