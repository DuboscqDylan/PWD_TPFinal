<?php
<<<<<<< HEAD
include_once BASE_URL.'/configuracion.php';
=======
include_once '../../../../configuracion.php';
>>>>>>> 5ae2336 (accion)
$data = Funciones::data_submitted(); 

$respuesta = (new AbmProducto())->listarProductos();

echo json_encode($respuesta);
?>