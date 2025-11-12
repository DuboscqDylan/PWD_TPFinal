<?php
include_once ROOT_PATH."/configuracion.php";
include STRUCTURE_PATH."/Header.php";

$data = Funciones :: data_submitted();

if(!empty($data)){
    $arregloProductos = (new AbmProducto())->buscar(['idproducto' => $data['idproducto']]);
    $resultado = null;
    if(!empty($arregloProductos)){
        $producto = $arregloProductos[0];
        $rutaImagenes = BASE_URL."/Vista/Media/Producto/".$producto->getIdproducto();

        $scanHeader = glob($rutaImagenes.'/*.png');
        if(!empty($scanHeader)){
            $header = "<img class='' src='".$scanHeader[0]."' height='250' alt='portada'>";
        }else{
            $header = '<p>No hay portada</p>';
        }

        $scanDiv = glob($rutaImagenes."/Preview/*");
        if(!empty($scanDiv)){
            $previaDiv="";
            foreach($scanDiv as $ruta) {
                $previaDiv .= "<img class='' src='".$ruta."'>'";
            }
        }else{
            $previaDiv = "<p class=''> No hay imagenes </p>";
        }

        $resultado = "<div class=''>
                        <!---Portada--->
                        <div class=''>
                            <div class=''>
                                <h1>".$producto->getPronombre()."</h1>
                            </div>
                            <div class=''>
                            ".$header."
                            </div>
                        <div>
                        <!---Detalle--->
                        <div class=''>
                            <p>".$producto->getProdetalle()."</p>
                        </div>
                        <div class=''>
                        </div>
                    </div>";
    }else{
        $resultado = "<h3> No se encontró el producto </h3>";
    }
}else{
    $resultado = "<h3> No se seleccionó un producto </h3>";
}

echo $resultado;

include STRUCTURE_PATH . "/Footer.php";

?>

