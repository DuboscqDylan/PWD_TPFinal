<?php
include_once '../../../../configuracion.php';
$data = Funciones::data_submitted();

$respuesta = (new ABMProducto())->listarProductos($data); 
echo json_encode($respuesta);
?>