
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
                        <th>IDVideoYT</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los productos serán cargados dinámicamente aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulario Estático para Alta de Producto -->
    <div class="card shadow border-0 rounded-4 p-4 bg-dark text-white mb-5" style="max-width: 45%; padding: 20px;">
        <h2 class="fw-semibold mb-4 text-success">Alta de Producto</h2>
        <form id="altaProductoForm">
            <div class="mb-3">
                <label for="nombreAlta" class="form-label text-white">Nombre</label>
                <input type="text" class="form-control" id="nombreAlta" name="nombreAlta" required>
            </div>
            <div class="mb-3">
                <label for="detalleAlta" class="form-label text-white">Detalle</label>
                <input type="text" class="form-control" id="detalleAlta" name="detalleAlta" required>
            </div>
            <div class="mb-3">
                <label for="stockAlta" class="form-label text-white">Stock</label>
                <input type="number" class="form-control" id="stockAlta" name="stockAlta" required>
            </div>
            <div class="mb-3">
                <label for="precioAlta" class="form-label text-white">Precio</label>
                <input type="number" class="form-control" id="precioAlta" name="precioAlta" required>
            </div>
            <div class="mb-3">
                <label for="idvideoytAlta" class="form-label text-white">Id Youtube</label>
                <input type="text" class="form-control" id="idvideoytAlta" name="idvideoytAlta">
            </div>

            <button type="submit" class="btn btn-success mt-3 w-100">Crear producto</button>
        </form>
        <div class="mt-3">
            <div id="errorMessage" class="alert alert-danger d-none text-center"></div>
            <div id="successMessage" class="alert alert-success d-none text-center"></div>
        </div>
    </div>

    <!-- Formulario Modificar Producto -->
    <div class="card shadow border-0 rounded-4 p-4 bg-dark text-white mb-5" style="max-width: 45%; padding: 20px;">
        <h2 class="fw-semibold mb-4 text-primary">Modificar Producto</h2>
        <form id="modificarProductoForm">
            <div class="mb-3">
                <label for="idproducto" class="form-label text-white">Id</label>
                <input type="number" class="form-control" id="idproducto" name="idproducto" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label text-white">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="mb-3">
                <label for="detalle" class="form-label text-white">Detalle</label>
                <input type="text" class="form-control" id="detalle" name="detalle">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label text-white">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label text-white">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio">
            </div>
            <div class="mb-3">
                <label for="idvideoyt" class="form-label text-white">Id Youtube</label>
                <input type="text" class="form-control" id="idvideoyt" name="idvideoyt">
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-100">Actualizar producto</button>
        </form>
        <div class="mt-3">
            <div id="errorMessageMod" class="alert alert-danger d-none text-center"></div>
            <div id="successMessageMod" class="alert alert-success d-none text-center"></div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        cargarProductos();

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
                                <td>${producto.idvideoyt}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <button class="my-1 btn btn-danger btn-sm" onclick="bajaProducto(${producto.idproducto})">Eliminar</button>
                                        ${botonEstado}
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

        // (El resto del JS no se toca)

        
        // Manejo de alta de producto
        $('#altaProductoForm').submit(function(e) {
            e.preventDefault(); // Evita el envío por defecto del formulario
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');

            // Recopilación de los datos del formulario
            const formData = {
                nombre: $('#nombreAlta').val(),
                detalle: $('#detalleAlta').val(),
                stock: $('#stockAlta').val(),
                precio: $('#precioAlta').val(),
                idvideoyt: $('#idvideoytAlta').val()
            };

            // Envío de los datos a través de Ajax
            $.ajax({
                url: 'Accion/Alta.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    //console.log(response);
                    const res = JSON.parse(response);
                    if (res.success) {
                        $('#successMessage')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarProductos(); // Recargar la lista de productos
                    } else {
                        $('#errorMessage')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#errorMessage')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
        });

        //baja de producto
        window.bajaProducto = function(idproducto) {
            if (confirm('¿Está seguro que desea eliminar el producto?')) {
                $.ajax({
                    url: 'Accion/Baja.php',
                    type: 'POST',
                    data: {
                        idproducto: idproducto
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            alert(res.message);
                            $('#producto-' + idproducto).remove();
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            }
        }
        // deshabilitado de producto
        window.deshabilitarProducto = function(idproducto) {
            if (confirm('¿Está seguro que desea deshabilitar el producto?')) {
                $.ajax({
                    url: 'Accion/Deshabilitar.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idproducto: idproducto 
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            cargarProductos(); // Recargar la lista de productos
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }

                });
            }
        }
        // habilitado de producto
        window.habilitarProducto = function(idproducto) {
            if (confirm('¿Está seguro que desea habilitar el producto?')) {
                $.ajax({
                    url: 'Accion/Habilitar.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idproducto: idproducto // hay que usar si o si idproducto, claro
                    },
                    success: function(response) {
                        if (response) {
                            alert(response.message);
                            cargarProductos(); // Recargar la lista de productos tampoco
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            }
        };


        $('#modificarProductoForm').submit(function(e) {
            e.preventDefault(); // Evita el envío por defecto del formulario
            $('#errorMessageMod').text('').removeClass('d-block').addClass('d-none');
            $('#successMessageMod').text('').removeClass('d-block').addClass('d-none');

            var formData = {
                idproducto: parseInt($('#idproducto').val(), 10)
            }; // Convertir a entero

            if ($('#nombre').val().trim() != "") {
                formData.nombre = $('#nombre').val().trim();
            }
            if ($('#detalle').val().trim() != "") {
                formData.detalle = $('#detalle').val().trim();
            }
            if (!isNaN(parseInt($('#stock').val(), 10))) {
                formData.stock = parseInt($('#stock').val(), 10); // Validar si es NaN    
            }
            if (!isNaN(parseFloat($('#precio').val()))) {
                formData.precio = parseFloat($('#precio').val()); // Validar si es NaN
            }
            if ($('#idvideoyt').val().trim() != "") {
                formData.idvideoyt = $('#idvideoyt').val().trim();
            }
            
            $.ajax({
                url: 'Accion/Modificar.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const res = JSON.parse(response);
                    //console.log(res);
                    if (res.success) {
                        $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarProductos(); // Recargar la lista de productos
                    } else {
                        $('#errorMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#errorMessageMod')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
        });
    });

</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>
