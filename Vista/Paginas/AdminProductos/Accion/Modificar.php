<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

$resp = (new AbmProducto())->modificarProductos($data);

echo json_encode($resp);
