<?php
include_once $_SERVER['DOCUMENT_ROOT']."/configuracion.php";

$data = Funciones::data_submitted();

$respuesta = (new ABMProducto())->Alta($data);

echo json_encode($respuesta);
?>
