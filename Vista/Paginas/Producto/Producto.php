<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/Header.php";

$data = data_submitted();

if (!empty($data)) {

    $arregloProductos = (new AbmProducto())->buscar(['idproducto' => $data['idproducto']]);

    if (!empty($arregloProductos)) {

        $producto = $arregloProductos[0];

        // Ruta física para buscar archivos
        $rutaImagenesFS = $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/Vista/Media/Producto/" . $producto->getIdproducto();

        // Ruta web para mostrarlos
        $rutaImagenesWEB = BASE_URL . "/Vista/Media/Producto/" . $producto->getIdproducto();

        // Buscamos portadas
        $scanHeader = glob($rutaImagenesFS . '/*.png');

        if (!empty($scanHeader)) {
            $nombreArchivo = basename($scanHeader[0]);
            $header = "<img class='img-fluid rounded shadow-sm' src='" . $rutaImagenesWEB . "/" . $nombreArchivo . "' height='250' alt='portada'>";
        } else {
            $header = '<p class="text-muted text-center">No hay portada</p>';
        }

        // Buscamos imágenes de Preview
        $scanDiv = glob($rutaImagenesFS . "/Preview/*");

        if (!empty($scanDiv)) {
            $previaDiv = "";
            foreach ($scanDiv as $ruta) {
                $archivo = basename($ruta);
                $previaDiv .= "<img class='img-thumbnail m-2 shadow-sm' src='" . $rutaImagenesWEB . "/Preview/" . $archivo . "'>";
            }
        } else {
            $previaDiv = "<p class='text-muted fst-italic text-center'> No hay imagenes </p>";
        }
        $resultado = "
        <div class='container my-5'>
            <!---Portada--->
            <div class='card shadow-lg border-0 rounded-4 mb-4 p-4'>
                <div class='card-header bg-primary text-white text-center rounded-top-4'>
                    <h1 class='fw-bold mb-0'>" . $producto->getPronombre() . "</h1>
                </div>
                <div class='card-body text-center'>
                    " . $header . "
                </div>
            </div>

            <!---Detalle--->
            <div class='card border-0 shadow-sm rounded-4 p-4 mb-4'>
                <p class='lead text-muted'>" . $producto->getProdetalle() . "</p>
            </div>

            <div class='d-flex flex-wrap justify-content-center'>
                " . $previaDiv . "
            </div>
        </div>";
    } else {
        $resultado = "<h3 class='text-danger text-center mt-5'> No se encontró el producto </h3>";
    }
} else {
    $resultado = "<h3 class='text-warning text-center mt-5'> No se seleccionó un producto </h3>";
}

echo $resultado;

include STRUCTURE_PATH . "/Footer.php";
