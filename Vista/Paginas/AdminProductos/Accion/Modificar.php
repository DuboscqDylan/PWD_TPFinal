<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();  // Obtener los datos enviados

$respuesta = (new AbmProducto())->modificar($data);

echo json_encode($respuesta);
