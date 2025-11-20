<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php";
?>

<div class="container my-5">

    <!-- Tabla de compras -->
    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 100%;">

        <!-- Compras Entrantes -->
        <div class="mb-5">
            <h1 class="fw-bold text-primary mb-4">Compras Entrantes</h1>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="comprasEntrantesTable" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las compras son cargadas dinámicamente acá-->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compras completadas -->
        <div class="mb-5">
            <h1 class="fw-bold text-success mb-4">Compras Concretadas</h1>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="comprasConcretadasTable" style="width: 100%;">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las compras son cargadas dinámicamente acá -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compras canceladas -->
        <div>
            <h1 class="fw-bold text-danger mb-4">Compras Canceladas</h1>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="comprasCanceladasTable" style="width: 100%;">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las compras son cargadas dinámicamente acá -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        cargarComprasEntrantes();
        cargarComprasConcretadas();
        cargarComprasCanceladas();
    });

    function cargarComprasEntrantes() {
        $.ajax({
            url: 'Accion/ListarCompraEstados.php',
            method: 'GET',
            data: { idcompraestadotipo: 2 },
            dataType: 'json',
            success: function(response) {
                var tableContent = '';
                $.each(response, function(index, compra) {
                    tableContent += `
                        <tr id="compra-${compra.idcompra}">
                            <td>${compra.idcompra}</td>
                            <td>${compra.estado}</td>
                            <td>${compra.cefechaini}</td>
                            <td>${compra.cefechafin ?? 'N/A'}</td>
                            <td>${compra.usuario}</td>
                            <td>
                                <button class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" onclick="enviarCompra(${compra.idcompraestado})">Enviar</button>
                                <button class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" onclick="cancelarCompra(${compra.idcompraestado})">Cancelar</button>
                            </td>
                        </tr>
                    `;
                });
                $('#comprasEntrantesTable tbody').html(tableContent);
            }
        });
    }

    function cargarComprasConcretadas() {
        $.ajax({
            url: 'Accion/ListarCompraEstados.php',
            method: 'GET',
            data: { idcompraestadotipo: 3 },
            dataType: 'json',
            success: function(response) {
                var tableContent = '';
                $.each(response, function(index, compra) {
                    tableContent += `
                        <tr id="compra-${compra.idcompra}">
                            <td class="bg-success text-white">${compra.idcompra}</td>
                            <td class="bg-success text-white">${compra.estado}</td>
                            <td class="bg-success text-white">${compra.cefechaini}</td>
                            <td class="bg-success text-white">${compra.cefechafin ?? 'N/A'}</td>
                            <td class="bg-success text-white">${compra.usuario}</td>
                        </tr>
                    `;
                });
                $('#comprasConcretadasTable tbody').html(tableContent);
            }
        });
    }

    function cargarComprasCanceladas() {
        $.ajax({
            url: 'Accion/ListarCompraEstados.php',
            method: 'GET',
            data: { idcompraestadotipo: 4 },
            dataType: 'json',
            success: function(response) {
                var tableContent = '';
                $.each(response, function(index, compra) {
                    tableContent += `
                        <tr id="compra-${compra.idcompra}">
                            <td class="bg-danger text-white">${compra.idcompra}</td>
                            <td class="bg-danger text-white">${compra.estado}</td>
                            <td class="bg-danger text-white">${compra.cefechaini}</td>
                            <td class="bg-danger text-white">${compra.cefechafin ?? 'N/A'}</td>
                            <td class="bg-danger text-white">${compra.usuario}</td>
                        </tr>
                    `;
                });
                $('#comprasCanceladasTable tbody').html(tableContent);
            }
        });
    }

    function enviarCompra(idcompraEstado) {
        if (confirm("¿Desea enviar la compra?")) {
            $.ajax({
                url: 'Accion/CambiarEstado.php',
                method: 'POST',
                data: {
                    idcompraestado: idcompraEstado,
                    idnuevoestadotipo: 3
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        cargarComprasEntrantes();
                        cargarComprasConcretadas();
                        cargarComprasCanceladas();
                    }
                }
            });
        }
    }

    function cancelarCompra(idcompraEstado) {
        if (confirm("¿Desea cancelar la compra?")) {
            $.ajax({
                url: 'Accion/CambiarEstado.php',
                method: 'POST',
                data: {
                    idcompraestado: idcompraEstado,
                    idnuevoestadotipo: 4
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        cargarComprasEntrantes();
                        cargarComprasConcretadas();
                        cargarComprasCanceladas();
                    }
                }
            });
        }
    }
</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>
