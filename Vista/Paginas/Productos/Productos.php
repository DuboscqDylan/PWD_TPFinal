<?php
include_once '../../../configuracion.php';
include STRUCTURE_PATH . '/Head.php';

$data = Funciones::data_submitted();

if (!empty($data)) {
    $arrProductos = (new ABMProducto())->buscar(['idproducto' => $data['idproducto']]);
    $resultado = null;
    if (!empty($arrProductos)) {
        $producto = $arrProductos[0];
        $dirImagenes = "../../Media/Product/".$producto->getIdproducto();

        // glob escanea la dirección y devuelve un array con las imágenes en el directorio (no incluye subcarpetas)
        $scanHeader = glob($dirImagenes.'/*.png');
        if (!empty($scanHeader)) {
            $header = "<img class='' src='".$scanHeader[0]."' height='250' alt='portada'>";
        } else {
            $header = '<p> Sin Portada </p>';
        }
        
        $scanDiv = glob($dirImagenes."/Preview/*");
        if (!empty($scanDiv)) {
            $previewDiv = "";
            foreach($scanDiv as $cadaRuta) {
                $previewDiv .= "<img class='m-1' src='".$cadaRuta."'>";
            }
        } else {
            $previewDiv = '<p class="m-auto"> Sin imagenes de muestra </p>'; //No hay imagenes de muestra
        }

        // Código HTML
        $resultado = "
        <div class=''>
                <div class=''>

                    <div class=''>
                        <div class=''>
                            <!-- PORTADA DEL PRODUCTO -->
                            <div class=''>
                                
                                <h1>".$producto->getPronombre()."</h1>
                                
                                <div class=''>
                                    " . $header . "
                                </div>
                            </div>
                        </div>

                        <div class=''>
                            <!--- TEXTO DESCRIPTIVO DEL PRODUCTO --->
                            <p> ".$producto->getProdetalle()." </p>
                        </div>
                        
                    </div>
                    
                    <div class='' style='height: 200px'>
                        ".$previewDiv."
                    </div>
                    
                </div>

                <div class=''>
                    <!-- TRAILER/VIDEO/IMAGEN AL RESPECTO SI FUERA EL CASO -->
                    <div class=''>
                        <iframe width='800' height='600' class='bg-steam-lightgreen bdr-steam-nofocus' src='https://www.youtube.com/embed/".$producto->getIdvideoyt()."' frameborder='0' allowfullscreen></iframe>                                 
                    </div>
                </div>                
        </div>
           ";

    } else {
        $resultado = '<h2> PRODUCTO NO ENCONTRADO </h2>';
    }
} else {
    $resultado = '<h2> NO SE HA SELECCIONADO UN PRODUCTO </h2>';
}
?>

<?php echo $resultado; ?>

<?php include STRUCTURE_PATH . '/Foot.php'; ?>