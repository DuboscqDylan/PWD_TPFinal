<?php 
include_once BASE_URL. '/configuracion.php';
$data = Funciones::data_submitted();

$respuesta = (new AbmCompra())->comprarCarrito($data); 

echo json_encode($respuesta);
?>