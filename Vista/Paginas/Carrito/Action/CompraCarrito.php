<?php 
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted();

$respuesta = (new ABMCompra())->comprarCarrito($data); //['idcompraestado(ESTADO ACTUAL)']

echo json_encode($respuesta);
?>