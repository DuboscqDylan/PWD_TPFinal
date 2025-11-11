<?php
include_once BASE_URL.'/configuracion.php';
$data = Funciones::data_submitted(); 

$respuesta = (new Session())->iniciarSesion($data);
 
echo json_encode($respuesta);
?>