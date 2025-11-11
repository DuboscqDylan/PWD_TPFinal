<?php
include_once "../../../configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php";
?>

<div class="">

    <!-- Tabla de Productos -->
    <div class="" style="max-width: 65%; padding: 20px;">
        <h1>Administrar Productos</h1>
        <table class="" id="productosTable" style="width: 100%;">
            <thead class="">
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

    <!-- Formulario Estático para Alta de Producto -->
    <div class="" style="max-width: 45%; padding: 20px;">
        <h2>Alta de Producto</h2>
        <form id="altaProductoForm">
            <div class="">
                <label for="nombreAlta" class="text-white">Nombre</label>
                <input type="text" class="" id="nombreAlta" name="nombreAlta" required>
            </div>
            <div class="">
                <label for="detalleAlta" class="text-white">Detalle</label>
                <input type="text" class="" id="detalleAlta" name="detalleAlta" required>
            </div>
            <div class="">
                <label for="stockAlta" class="text-white">Stock</label>
                <input type="number" class="" id="stockAlta" name="stockAlta" required>
            </div>
            <div class="">
                <label for="precioAlta" class="text-white">Precio</label>
                <input type="number" class="" id="precioAlta" name="precioAlta" required>
            </div>
            <div class="">
                <label for="idvideoytAlta" class="text-white">Id Youtube</label>
                <input type="text" class="" id="idvideoytAlta" name="idvideoytAlta">
            </div>

            <button type="submit" class="btn btn-success mt-3">Crear producto</button>
        </form>
        <div class="">
            <div id="errorMessage" class=""></div>
            <div id="successMessage" class=""></div>
        </div>
    </div>


    <div class="" style="max-width: 45%; padding: 20px;">
        <h2>Modificar Producto</h2>
        <form id="modificarProductoForm">
            <div class="">
                <label for="idproducto" class="text-white">Id</label>
                <input type="number" class="form-control" id="idproducto" name="idproducto" required>
            </div>
            <div class="">
                <label for="nombre" class="text-white">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="">
                <label for="detalle" class="text-white">Detalle</label>
                <input type="text" class="form-control" id="detalle" name="detalle">
            </div>
            <div class="">
                <label for="stock" class="text-white">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
            <div class="">
                <label for="precio" class="text-white">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio">
            </div>
            <div class="">
                <label for="idvideoyt" class="text-white">Id Youtube</label>
                <input type="text" class="form-control" id="idvideoyt" name="idvideoyt">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Actualizar producto</button>
        </form>
        <div class="">
            <div id="errorMessageMod" class="text text-center mt-3 p-2 bg-danger rounded-3 w-100 text-white d-none"></div>
            <div id="successMessageMod" class="text text-center mt-3 p-2 bg-success rounded-3 w-100 text-white d-none"></div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        cargarProductos();

        function cargarProductos() {
            $.ajax({
                url: 'Action/ListarProductos.php',
                method: 'POST',
                data: 'todo',
                dataType: 'json',
                success: function(response) {
                    var tableContent = '';
                    $.each(response, function(index, producto) {
                        // Determinar estado y el botón correspondiente
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
                        <div class=""> 
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
                url: 'Action/AltaProducto.php',
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
                    url: 'Action/BajaProductos.php',
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
                    url: 'Action/DeshabilitarProducto.php',
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
                    url: 'Action/HabilitarProducto.php',
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
                url: 'Action/ModificacionProductos.php',
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

<?php include STRUCTURE_PATH . "/Foot.php"; ?>