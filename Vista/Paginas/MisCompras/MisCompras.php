<?php
include_once '../../../configuracion.php';
include STRUCTURE_PATH . '/HeadSafe.php';
?>

<div class="d-flex justify-content-center align-items-start gap-3">
    <!-- Tabla de Productos -->
    <div class="mt-5" style="max-width: 100%; padding: 20px; width:1200px;">
        <h1 class="text-center">Mis Compras</h1>
        <table class="table table-bordered table-striped" id="comprasPersonalesTable" style="width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las compras serán cargadas dinámicamente aquí -->
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        listarComprasPersonales();
    });

    function listarComprasPersonales() {
        $.ajax({
            url: 'Action/ListarCompras.php',
            method: 'POST',
            data: {
                idusuario: <?php echo $usuario->getIdusuario(); ?>
            },
            dataType: 'json',
            success: function(response) {
                var tableContent = '';

                $.each(response, function(index, compra) {
                    var estadoClass = '';
                    if (compra.estado == 'Cancelada') {
                        estadoClass = 'bg-danger text-white';
                    } else if (compra.estado == 'Enviada') {
                        estadoClass = 'bg-success text-white';
                    } else if (compra.estado == 'Aceptada') {
                        estadoClass = 'bg-primary text-white';
                    }

                    tableContent += `
                        <tr>
                            <td>${compra.cofecha}</td>
                            <td>
                    `;

                    $.each(compra.items, function(index, item) {
                        tableContent += `${item.pronombre} x ${item.cicantidad}<br>`;
                    });

                    let total = 0;
                    $.each(compra.items, function(index, item) {
                        total += item.proprecio * item.cicantidad;
                    });

                    tableContent += `
                            </td>
                            <td>$${total.toFixed(2)}</td>
                            <td class="${estadoClass}">${compra.estado}</td>
                            <td>
                    `;

                    if (compra.estado == 'Aceptada') {
                        tableContent += `
                                <button class="btn btn-danger btn-sm" onclick="cancelarCompra(${compra.idcompraestado})">Cancelar</button>
                        `;
                    } else {
                        tableContent += `-`; 
                    }

                    tableContent += `
                            </td>
                        </tr>
                    `;
                });

                $('#comprasPersonalesTable tbody').html(tableContent);
            },
            error: function() {
                alert('Error al cargar compras.');
            }
        });
    }

    function cancelarCompra(idcompraestado) {
        if (confirm('¿Estás seguro de que deseas cancelar esta compra?')) {
            $.ajax({
                url: 'Action/CancelarCompra.php',
                method: 'POST',
                data: {
                    idcompraestado: idcompraestado,
                    idnuevoestadotipo: 4 // Cancelada
                },
                success: function(response) {
                    listarComprasPersonales();
                },
                error: function() {
                    alert('Error al intentar cancelar la compra.');
                }
            });
        }
    }
</script>



<?php include STRUCTURE_PATH . '/Foot.php'; ?>