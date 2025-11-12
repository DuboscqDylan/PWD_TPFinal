<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted();

$respuesta = (new AbmProducto())->listarProductos($data); //Obtiene array unitario con producto idproducto en $data

echo json_encode($respuesta);
?>