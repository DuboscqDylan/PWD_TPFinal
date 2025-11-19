<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';
?>

<!-- Contenido principal -->
<div class="container my-5">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">

            <h1 class="fw-bold text-primary mb-4 text-center">Mis Compras</h1>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="comprasPersonalesTable" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>Fecha</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
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
        listarComprasPersonales();
    });

    function listarComprasPersonales() {
        $.ajax({
            url: 'Accion/ListarCompras.php',
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
                        estadoClass = 'badge bg-danger';
                    } else if (compra.estado == 'Enviada') {
                        estadoClass = 'badge bg-success';
                    } else if (compra.estado == 'Aceptada') {
                        estadoClass = 'badge bg-primary';
                    }

                    tableContent += `
                        <tr>
                            <td>${compra.cofecha}</td>
                            <td>
                    `;

                    $.each(compra.items, function(index, item) {
                        tableContent += `<div>${item.pronombre} <span class="text-muted">x ${item.cicantidad}</span></div>`;
                    });

                    let total = 0;
                    $.each(compra.items, function(index, item) {
                        total += item.proprecio * item.cicantidad;
                    });

                    tableContent += `
                            </td>
                            <td><strong>$${total.toFixed(2)}</strong></td>
                            <td><span class="${estadoClass}">${compra.estado}</span></td>
                            <td>
                    `;

                    if (compra.estado == 'Aceptada') {
                        tableContent += `
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm" onclick="cancelarCompra(${compra.idcompraestado})">
                                Cancelar
                            </button>
                        `;
                    } else {
                        tableContent += `<span class="text-muted">-</span>`;
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
                url: 'Accion/CancelarCompra.php',
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

<?php include STRUCTURE_PATH . '/Footer.php'; ?>