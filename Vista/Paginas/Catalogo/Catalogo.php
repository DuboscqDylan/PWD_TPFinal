<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';

$esCliente = $sesion->esCliente();
?>

<div class="container my-5">
    <h1 class="text-center mb-5 text-primary fw-bold">Productos</h1>
    
    <!-- Grilla de productos -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="catalogo">
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajax({
        url: 'Accion/ListarProductos.php',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            var htmlContent = '';
            $.each(response, function(index, producto) {
                htmlContent += `
                <div class="col">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="p-3">
                            <a href='<?php echo BASE_URL ?>/Vista/Paginas/Producto/Producto.php?idproducto=${producto.idproducto}'>
                                <img src='${producto.icon}' class="img-fluid rounded" style="max-height:150px; object-fit:contain;" alt='${producto.pronombre}'>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">${producto.pronombre}</h5>
                            <p class="card-text text-muted mb-1">Precio: $${producto.proprecio}</p>
                            <p class="card-text">Stock: ${producto.procantstock}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a class="btn btn-primary w-100 mb-2" href='<?php echo BASE_URL ?>/Vista/Paginas/Producto/Producto.php?idproducto=${producto.idproducto}'>Ver detalles</a>
                            <?php if ($esCliente) { ?> 
                                <button class="btn btn-outline-primary w-100" id="${producto.idproducto}" onclick="agregarItemCarrito(${producto.idproducto})">Agregar al carro</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>`;
            });
            $('#catalogo').html(htmlContent);
        },
        error: function() {
            alert('Error al cargar el catálogo.');
        }
    });
});

function agregarItemCarrito(idprod) {
    $.ajax({
        url: 'Accion/AgregarCompraItem.php',
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
                 actualizarIconoCarrito();
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
        url: 'Accion/ListarProductos.php',
        method: 'POST',
        data: { idproducto: idprod },
        dataType: 'json',
        success: function(response) {
            var htmlContent = '';
            $.each(response, function(index, producto) {
                htmlContent += `
                    <div class="text-center">
                        <a href='<?php echo BASE_URL ?>/Vista/Paginas/Producto/Producto.php?idproducto=${producto.idproducto}'>
                            <img src='${producto.icon}' class="img-fluid rounded" style="max-height:150px; object-fit:contain;" alt='${producto.pronombre}'>
                        </a>
                        <h5 class="fw-bold mt-2">${producto.pronombre}</h5>
                        <p class="text-muted mb-1">Precio: $${producto.proprecio}</p>
                        <p>Stock: ${producto.procantstock}</p>
                    </div>`;
            });
            $('#detailsProducto'+idprod).html(htmlContent);
        },
        error: function() {
            alert('Error al cargar el catálogo.');
        }
    });
}
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>
