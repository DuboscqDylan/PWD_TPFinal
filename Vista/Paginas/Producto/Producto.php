<?php
include_once BASE_URL."/configuracion.php";
include STRUCTURE_PATH."/Header.php";

$data = Funciones :: data_submitted();

if(!empty($data)){
    $arregloProductos = (new AbmProducto())->buscar(['idproducto' => $data['idproducto']]);
    $resultado = null;
    if(!empty($arregloProductos)){
        $producto = $arregloProductos[0];
        $rutaImagenes = BASE_URL."/Vista/Media/Producto/".$producto->getIdproducto();

        $scan = glob($rutaImagenes.'/*.png');
        if(!empty($scan)){
            
        }
    }
}