<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$sesion = new Session();
$sesion->cerrar();
header('Location: '.BASE_URL.'/Vista/Paginas/Catalogo/Catalogo.php');