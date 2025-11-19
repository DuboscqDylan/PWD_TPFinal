<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$respuesta = (new AbmProducto())->listarProductos($data); //Obtiene array unitario con producto idproducto en $data

echo json_encode($respuesta);
