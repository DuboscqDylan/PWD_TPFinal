<?php 
include_once '../../../configuracion.php';
$sesion = new Session();
$sesion->cerrar();
header('Location: '.BASE_URL.'/View/Pages/Catalogo/Catalogo.php');