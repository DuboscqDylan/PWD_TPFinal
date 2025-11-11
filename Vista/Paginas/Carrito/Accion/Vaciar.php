<?php 
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted();

$respuesta = (new ABMCompraItem())->vaciarCarrito($data);

echo json_encode($respuesta);
?>