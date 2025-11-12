<?php
include_once $_SERVER['DOCUMENT_ROOT']."/configuracion.php";

$data = Funciones::data_submitted(); 

$respuesta = (new AbmProducto())->Listar();

echo json_encode($respuesta);
?>
