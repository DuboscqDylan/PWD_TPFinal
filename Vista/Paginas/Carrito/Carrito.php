<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php";
?>

<!-- Main para que el footer quede abajo -->
<main class="d-flex flex-column flex-grow-1">
    <div class="container my-5">
        <h2 class="text-center mb-4 fw-bold text-success">🛒 Carrito de Compras</h2>
        <div id="carrito" class="row g-3 justify-content-center min-vh-50">
            <!-- Acá se cargarán los productos -->
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-success px-4 me-2" onclick="comprarCarrito()">Comprar</button>
            <button class="btn btn-danger px-4" onclick="vaciarCarrito()">Vaciar carrito</button>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        actualizarCarrito();
    });

    function actualizarCarrito() {
        $.ajax({
            url: '<?php echo BASE_URL; ?>/Vista/Paginas/Carrito/Accion/ListarCarrito.php',
            method: 'POST',
            data: {
                idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0 ?>
            },
            dataType: 'json',
            success: function(respuesta) {
                let html = '';
                let total = 0;

                if (respuesta.length === 0) {
                    $('#carrito').html(`
                    <div class="col-12 d-flex align-items-center justify-content-center text-center text-muted fs-5" style="height: 50vh;">
                        🕳️ Tu carrito está vacío.
                    </div>
                `);
                    return;
                }

                respuesta.forEach(item => {
                    const subtotal = item.proprecio * item.cicantidad;
                    total += subtotal;

                    html += `
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3">
                        <div class="d-flex align-items-center">
                            <img src="${item.icon}" class="rounded me-3" style="width:100px;height:100px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1 text-dark">${item.pronombre}</h5>
                                <p class="text-secondary small mb-1">Stock: ${item.procantstock}</p>
                                <p class="fw-semibold mb-0 text-success">$${parseFloat(item.proprecio).toFixed(2)}</p>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-outline-success btn-sm" onclick="sumar(${item.idproducto},1)">+</button>
                                <p class="my-1 fw-bold">${item.cicantidad}</p>
                                <button class="btn btn-outline-danger btn-sm" onclick="restar(${item.idproducto},1)">-</button>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <span class="fw-semibold text-dark">Subtotal: $${subtotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>`;
                });

                html += `
            <div class="col-12 text-center mt-4">
                <h4 class="fw-bold text-dark">Total: $${total.toFixed(2)}</h4>
            </div>`;

                $('#carrito').html(html);
            },
            error: function() {
                alert('Error al cargar el carrito.');
            }
        });
    }

    function sumar(idproducto, cantidad) {
        $.post('<?php echo BASE_URL ?>/Vista/Paginas/Carrito/Accion/Sumar.php', {
            idproducto: idproducto,
            idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra() : 0; ?>,
            cicantidad: cantidad
        }, function() {
            actualizarCarrito();
        }, 'json');
    }

    function restar(idproducto, cantidad) {
        $.post('<?php echo BASE_URL ?>/Vista/Paginas/Carrito/Accion/Restar.php', {
            idproducto: idproducto,
            idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra() : 0; ?>,
            cicantidad: cantidad
        }, function() {
            actualizarCarrito();
        }, 'json');
    }

    function vaciarCarrito() {
        $.post('<?php echo BASE_URL ?>/Vista/Paginas/Carrito/Accion/Vaciar.php', {
            idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra() : 0; ?>
        }, function(respuesta) {
            alert(respuesta.message);
            actualizarCarrito();
        }, 'json');
    }

    function comprarCarrito() {
        if (!confirm("¿Deseas realizar la compra?")) return;
        $.post('<?php echo BASE_URL ?>/Vista/Paginas/Carrito/Accion/ComprarCarrito.php', {
            idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0; ?>,
            idnuevoestadotipo: 2
        }, function(respuesta) {
            alert(respuesta.message);
            if (respuesta.success)
                window.location.href = "<?php echo BASE_URL ?>/Vista/Paginas/MisCompras/MisCompras.php";
        }, 'json');
    }
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>