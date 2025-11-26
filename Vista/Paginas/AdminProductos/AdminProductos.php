<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php";
?>

<div class="container-fluid my-5 d-flex flex-wrap justify-content-around">

    <!-- Tabla de Productos -->
    <div class="card shadow-lg border-0 rounded-4 p-4 bg-light mb-5" style="max-width: 65%; padding: 20px;">
        <h1 class="fw-bold text-primary mb-4">Administrar Productos</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="productosTable" style="width: 100%;">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Detalle</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los productos se cargan dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulario Alta Producto -->
    <div class="card shadow border-0 rounded-4 p-4 bg-dark text-white mb-5" style="max-width: 45%; padding: 20px;">
        <h2 class="fw-semibold mb-4 text-success">Alta de Producto</h2>
        <form id="altaProductoForm">
            <div class="mb-3">
                <label class="form-label text-white">Nombre</label>
                <input type="text" class="form-control" id="nombreAlta" name="nombreAlta" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Detalle</label>
                <input type="text" class="form-control" id="detalleAlta" name="detalleAlta" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Stock</label>
                <input type="number" class="form-control" id="stockAlta" name="stockAlta" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Precio</label>
                <input type="number" class="form-control" id="precioAlta" name="precioAlta" required>
            </div>

            <button type="submit" class="btn btn-success mt-3 w-100">Crear producto</button>
        </form>

        <div class="mt-3">
            <div id="errorMessage" class="alert alert-danger d-none text-center"></div>
            <div id="successMessage" class="alert alert-success d-none text-center"></div>
        </div>
    </div>

    <!-- Formulario Modificar Producto -->
   <div id="modificarCard" class="card shadow border-0 rounded-4 p-4 bg-dark text-white mb-5" 
     style="max-width: 45%; padding: 20px; display:none;">
    <h2 class="fw-semibold mb-4 text-primary">Modificar Producto</h2>

    <form id="modificarProductoForm">

        <input type="hidden" id="idproducto" name="idproducto">

        <div class="mb-3">
            <label class="form-label text-white">Detalle</label>
            <input type="text" class="form-control" id="prodetalle" name="prodetalle">
        </div>

        <div class="mb-3">
            <label class="form-label text-white">Stock</label>
            <input type="number" class="form-control" id="procantstock" name="procantstock">
        </div>

        <div class="mb-3">
            <label class="form-label text-white">Precio</label>
            <input type="number" class="form-control" id="proprecio" name="proprecio">
        </div>

        <button type="submit" class="btn btn-primary mt-3 w-100">Actualizar producto</button>
    </form>

    
</div>


</div>

<script>
$(document).ready(function() {

    cargarProductos();
    $('#cardModificar').hide(); //  empieza oculto

    function cargarProductos() {
        $.ajax({
            url: 'Accion/ListarProductos.php',
            method: 'POST',
            data: 'todo',
            dataType: 'json',
            success: function(response) {
                var tableContent = '';

                $.each(response, function(index, producto) {

                    const estado = producto.prodeshabilitado ? 'Deshabilitado' : 'Disponible';

                    const botonEstado = producto.prodeshabilitado ?
                        `<button class="my-1 btn btn-success btn-sm" onclick="habilitarProducto(${producto.idproducto})">Habilitar</button>` :
                        `<button class="my-1 btn btn-warning btn-sm" onclick="deshabilitarProducto(${producto.idproducto})">Deshabilitar</button>`;

                    tableContent += `
                        <tr id="producto-${producto.idproducto}">
                            <td>${producto.idproducto}</td>
                            <td>${producto.pronombre}</td>
                            <td>${producto.prodetalle}</td>
                            <td>${producto.procantstock}</td>
                            <td>${estado}</td>
                            <td>${producto.proprecio}</td>

                            <td>
                                <div class="d-flex flex-column align-items-start">
                                    ${botonEstado}

                                    <button class="my-1 btn btn-info btn-sm"
                                        onclick="cargarFormularioModificar(
                                            ${producto.idproducto},
                                            '${producto.prodetalle}',
                                            ${producto.procantstock},
                                            ${producto.proprecio}
                                        )">
                                        Modificar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#productosTable tbody').html(tableContent);
            },
            error: function() {
                alert('Error al cargar los productos.');
            }
        });
    }

    // ALTA
    $('#altaProductoForm').submit(function(e) {
        e.preventDefault();

        const formData = {
            pronombre: $('#nombreAlta').val(),
            prodetalle: $('#detalleAlta').val(),
            procantstock: $('#stockAlta').val(),
            proprecio: $('#precioAlta').val()
        };

        $.ajax({
            url: 'Accion/Alta.php',
            type: 'POST',
            data: formData,
            success: function(response) {

                const res = JSON.parse(response);

                if (res.success) {
                    $('#successMessage').text(res.message).removeClass('d-none').addClass('d-block');
                    cargarProductos();
                } else {
                    $('#errorMessage').text(res.message).removeClass('d-none').addClass('d-block');
                }
            },
            error: function() {
                $('#errorMessage').text('Error al procesar la solicitud.').removeClass('d-none').addClass('d-block');
            }
        });
    });

    // DESHABILITAR / HABILITAR
    window.deshabilitarProducto = function(idproducto) {
        if (confirm('¿Está seguro que desea deshabilitar el producto?')) {
            $.ajax({
                url: 'Accion/Deshabilitar.php',
                type: 'POST',
                dataType: 'json',
                data: { idproducto: idproducto },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        cargarProductos();
                    }
                }
            });
        }
    }

    window.habilitarProducto = function(idproducto) {
        if (confirm('¿Está seguro que desea habilitar el producto?')) {
            $.ajax({
                url: 'Accion/Habilitar.php',
                type: 'POST',
                dataType: 'json',
                data: { idproducto: idproducto },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        cargarProductos();
                    }
                }
            });
        }
    }

    // Cargar formulario Modificar
  window.cargarFormularioModificar = function(id, detalle, stock, precio) {

    $('#idproducto').val(id);
    $('#prodetalle').val(detalle);
    $('#procantstock').val(stock);
    $('#proprecio').val(precio);

    // Mostrar formulario
    $('#modificarCard').fadeIn(300);

    $('html, body').animate({
        scrollTop: $('#modificarCard').offset().top - 100
    }, 500);
};


    // MODIFICAR
    $('#modificarProductoForm').submit(function(e) {
        e.preventDefault();

       var formData = {
    idproducto: $('#idproducto').val(),
    detalle: $('#prodetalle').val(),
    stock: $('#procantstock').val(),
    precio: $('#proprecio').val()
};

        $.ajax({
            url: 'Accion/Modificar.php',
            type: 'POST',
            data: formData,
            success: function(response) {

                const res = JSON.parse(response);

                if (res.success) {
                    $('#modificarCard').fadeOut(300);
                   $('#modificarProductoForm')[0].reset();

                    $('#successMessageMod').text(res.message).removeClass('d-none');
                    cargarProductos();

                    setTimeout(() => {
                        $('#cardModificar').fadeOut(300); //  desaparece
                    }, 1200);

                } else {
                    $('#errorMessageMod').text(res.message).removeClass('d-none');
                }
            }
        });
    });

});
</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>
