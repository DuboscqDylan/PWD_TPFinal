<?php
include_once "../../../../configuracion.php";

$data = Funciones::data_submitted();

$respuesta = (new ABMProducto())->Alta($data);

echo json_encode($respuesta);
?>
