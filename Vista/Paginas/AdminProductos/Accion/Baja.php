<?php
include_once "../../../../configuracion.php";

$data = Funciones::data_submitted();

$respuesta = (new AbmProducto())->Baja($data);

echo json_encode($respuesta);
?>
