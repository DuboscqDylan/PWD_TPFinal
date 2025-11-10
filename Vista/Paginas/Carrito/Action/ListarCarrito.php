<?php
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new ABMCompraEstado())->listarCarrito($data);

echo json_encode($respuesta);
?>