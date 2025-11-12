<?php 
include_once ROOT_PATH."/configuracion.php";
$sesion = new Session();
$sesion->cerrar();
header('Location: '.BASE_URL.'/Vista/Paginas/Catalogo/Catalogo.php');