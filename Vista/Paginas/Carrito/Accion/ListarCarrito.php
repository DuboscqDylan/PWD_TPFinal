<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted(); 

$respuesta = (new ABMCompraEstado())->listarCarrito($data);

echo json_encode($respuesta);
?>