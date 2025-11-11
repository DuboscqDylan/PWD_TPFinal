<?php
include_once '../../../../configuracion.php';

$data = Funciones::data_submitted(); 

$respuesta = (new AbmProducto())->Listar();

echo json_encode($respuesta);
?>
