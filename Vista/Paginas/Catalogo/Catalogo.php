<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';

$esCliente = $sesion->esCliente();
?>

<div class="">
    <h1 class="text-center mb-4">Productos</h1>
    <!-- Product Grid -->
    <div class="" id="catalogo">
    </div>
</div>

<script>
    
    $(document).ready(function() {
        // Realizar la solicitud AJAX
        $.ajax({
            url: 'Action/ListarProductos.php', // Ruta al script PHP que genera los datos
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                var htmlContent = '';
                // Iterar sobre los productos y construir el HTML
                $.each(response, function(index, producto) {
                    htmlContent += `
                    <div class="">
                        <div class="">
                            <!-- Product Details -->
                            <div class = '' id='detailsProducto${producto.idproducto}'>
                                <div class=''>
                                    <div class=''>
                                        <!-- Product Image -->
                                        <a class='link-light text-decoration-none' href='<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${producto.idproducto}'>
                                            <img src='${producto.icon}'  width='100' height='100' alt='...'>
                                        </a>
                                    </div>

                                    <div class=''>
                                        <h5 class='card-title'> <a class='link-light text-decoration-none' href='<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${producto.idproducto}'> ${producto.pronombre}</a> </h5>
                                        <p class='card-text'> Precio: $ ${producto.proprecio} </p>
                                        <p class='card-text'> Stock: ${producto.procantstock} </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Product Actions -->
                            <div class=''>
                                <div class="">
                                    <div>
                                        <a class='btn btn-primary btn-steam w-100' href='<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${producto.idproducto}'>Ver detalles</a>
                                    </div>
                                    <div>
                                        <?php if ($esCliente) { ?> 
                                            <button class="btn btn-primary btn-steam w-100" id="${producto.idproducto}" onclick="agregarItemCarrito(${producto.idproducto})">Agregar al carro</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                // Insertar el HTML generado en el contenedor
                $('#catalogo').html(htmlContent);
            },
            error: function() {
                alert('Error al cargar el catálogo.');
            }
        });
    });


    function agregarItemCarrito(idprod) {
        $.ajax({
            url: 'Action/AgregarCompraItem.php',
            method: 'POST',
            data: {
                idproducto: idprod,
                idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra(): 0;?>, 
                cicantidad: 1,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    actualizarProducto(idprod);
                }
                alert(response.message);
            },
            error: function() {
                alert('Error al realizar operacion.');
            },
        });
    }

    function actualizarProducto(idprod) {
        $.ajax({
            url: 'Action/ObtenerProducto.php', // Ruta al script PHP que genera los datos
            method: 'POST',
            data: {
                idproducto: idprod
            },
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                // Iterar sobre los productos y construir el HTML
                var htmlContent = '';
                $.each(response, function(index, producto) {
                    htmlContent += `
                        <div class=''>
                            <div class=''>
                                <!-- Product Image -->
                                <a class='link-light text-decoration-none' href='<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${producto.idproducto}'>
                                    <img src='${producto.icon}'  width='100' height='100' alt='...'>
                                </a>
                            </div>

                            <div class=''>
                                <h5 class='card-title'> <a class='link-light text-decoration-none' href='<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${producto.idproducto}'> ${producto.pronombre}</a> </h5>
                                <p class='card-text'> Precio: $ ${producto.proprecio} </p>
                                <p class='card-text'> Stock: ${producto.procantstock} </p>
                            </div>
                        </div>`;
                });
                // Insertar el HTML generado en el contenedor
                $('#detailsProducto'+idprod).html(htmlContent);
            },
            error: function() {
                alert('Error al cargar el catálogo.');
            }
        });
    }
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>