<?php
include_once ROOT_PATH."/configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php";
?>

<div class="">

    <!-- Tabla de compras -->
    <div class="" style="max-width: 100%; padding: 20px;">
        <div>
            <h1>Compras Entrantes</h1>
            <table class="" id="comprasEntrantesTable" style="width: 100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th> <!-- id de compraestado -->
                        <th>Estado</th> <!-- idcompraestadotipo en compraestado -->
                        <th>Fecha Inicio</th> <!-- fechaInicio en compraestado -->
                        <th>Fecha Fin</th> <!-- fechaFin en compraestado -->
                        <th>Usuario</th> <!-- usuario relacionado con la compra -->
                        <th>Acción</th> <!-- Botones de acción -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Las compras serán cargadas dinámicamente aquí -->
                </tbody>
            </table>
        </div>
        <div>
            <h1>Compras concretadas</h1>
            <table class="" id="comprasConcretadasTable" style="width: 100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th> <!-- id de compraestado -->
                        <th>Estado</th> <!-- idcompraestadotipo en compraestado -->
                        <th>Fecha Inicio</th> <!-- fechaInicio en compraestado -->
                        <th>Fecha Fin</th> <!-- fechaFin en compraestado -->
                        <th>Usuario</th> <!-- usuario relacionado con la compra -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Las compras serán cargadas dinámicamente aquí -->
                </tbody>
            </table>
        </div>
        <div>
            <h1>Compras canceladas</h1>
            <table class="" id="comprasCanceladasTable" style="width: 100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th> <!-- id de compraestado -->
                        <th>Estado</th> <!-- idcompraestadotipo en compraestado -->
                        <th>Fecha Inicio</th> <!-- fechaInicio en compraestado -->
                        <th>Fecha Fin</th> <!-- fechaFin en compraestado -->
                        <th>Usuario</th> <!-- usuario relacionado con la compra -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Las compras serán cargadas dinámicamente aquí -->
                </tbody>
            </table>
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
            url: 'Action/ListarCompraEstados.php',
            method: 'GET',
            data: {
                idcompraestadotipo: 2
            },
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
                                    <button class="btn btn-success" onclick="enviarCompra(${compra.idcompraestado})">Enviar</button>
                                    <button class="btn btn-danger" onclick="cancelarCompra(${compra.idcompraestado})">Cancelar</button>
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
            url: 'Action/ListarCompraEstados.php',
            method: 'GET',
            data: {
                idcompraestadotipo: 3
            },
            dataType: 'json',
            success: function(response) {
                var tableContent = '';
                $.each(response, function(index, compra) {
                    tableContent += `
                            <tr id="compra-${compra.idcompra}">
                                <td class="bg-success text-white" >${compra.idcompra}</td>
                                <td class="bg-success text-white" >${compra.estado}</td>
                                <td class="bg-success text-white" >${compra.cefechaini}</td>
                                <td class="bg-success text-white" >${compra.cefechafin ?? 'N/A'}</td>
                                <td class="bg-success text-white" >${compra.usuario}</td>
                            </tr>
                        `;
                });
                $('#comprasConcretadasTable tbody').html(tableContent);
            }
        })
    }

    function cargarComprasCanceladas() {
        $.ajax({
            url: 'Action/ListarCompraEstados.php',
            method: 'GET',
            data: {
                idcompraestadotipo: 4
            },
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
        })
    }

    function enviarCompra(idcompraEstado) {
        if (confirm("¿Desea enviar la compra?")) {
            $.ajax({
                url: 'Action/CambiarEstado.php',
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
                url: 'Action/CambiarEstado.php',
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