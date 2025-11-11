<?php
<<<<<<< HEAD
include_once BASE.URL.'/configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new AbmCompra())->cambiarEstado($data);
=======
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new AbmCompra())->cambiarEstado($data); 
>>>>>>> 5ae2336 (accion)

echo json_encode($respuesta);
?>