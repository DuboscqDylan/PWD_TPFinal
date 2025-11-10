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
        <div class='row h-100 m-5'>
                <div class='h-100 col-6 bg-steam-lightgreen bdr-steam-nofocus align-content-center overflow-x-scroll'>

                    <div class='row d-flex flex-column align-items-center justify-content-between p-2'>
                        <div class='d-flex align-items-center justify-content-center overflow-x-scroll p-2'>
                            <!-- PORTADA DEL PRODUCTO -->
                            <div class='d-flex flex-column text-center'>
                                
                                <h1>".$producto->getPronombre()."</h1>
                                
                                <div class='bg-steam-lightgreen bdr-steam-nofocus p-1'>
                                    " . $header . "
                                </div>
                            </div>
                        </div>

                        <div class='text-center bg-steam-darkgreen bdr-steam-nofocus'>
                            <!--- TEXTO DESCRIPTIVO DEL PRODUCTO --->
                            <p> ".$producto->getProdetalle()." </p>
                        </div>
                        
                    </div>
                    
                    <div class='bg-steam-darkgreen bdr-steam-focus d-flex overflow-x-scroll w-75 m-auto' style='height: 200px'>
                        ".$previewDiv."
                    </div>
                    
                </div>

                <div class='col-6 bg-steam-lightgreen bdr-steam-nofocus d-flex overflow-x-scroll'>
                    <!-- TRAILER/VIDEO/IMAGEN AL RESPECTO SI FUERA EL CASO -->
                    <div class='m-auto'>
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