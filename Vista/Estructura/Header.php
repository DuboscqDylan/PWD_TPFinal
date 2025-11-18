<?php
include_once ROOT_PATH . '/Control/Session.php';

$sesion = new Session();
$sesionValida = $sesion->validar();
$menues = [];
$compraEstado = null;

if ($sesionValida) {
    $usuario = $sesion->getUsuario();
    $menues = $sesion->getMenues();
    $compraEstado = $sesion->crearCarrito();
}

// Construcción del menú dinámico
$menuHtml = "<ul class='navbar-nav me-auto mb-2 mb-lg-0'>";
$menuHtml .= "<li class='nav-item'><a class='nav-link' href='" . BASE_URL . "/Vista/Paginas/Catalogo/Catalogo.php'>Catálogo</a></li>";

foreach ($menues as $menu) {
    if ($menu->getPadre() != null) {
        $menuHtml .= "<li class='nav-item'><a class='nav-link' href='" . BASE_URL . $menu->getMeurl() . "'>" . $menu->getMenombre() . "</a></li>";
    }
}

$menuHtml .= "</ul>";

if ($sesionValida) {
    $menuHtml .= "
        <div class='d-flex'>
            <a class='btn btn-outline-light me-2' href='" . BASE_URL . "/Vista/Paginas/Perfil/Perfil.php'>" . $usuario->getUsnombre() . "</a>
            <a class='btn btn-danger' href='" . BASE_URL . "/Vista/Paginas/Logout/Logout.php'>Cerrar Sesión</a>
        </div>";
} else {
    $menuHtml .= "
        <div class='d-flex'>
            <a class='btn btn-outline-light me-2' href='" . BASE_URL . "/Vista/Paginas/Login/Login.php'>Iniciar Sesión</a>
            <a class='btn btn-success' href='" . BASE_URL . "/Vista/Paginas/Registrar/Registrar.php'>Registrarse</a>
        </div>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Programación Web Dinámica</title>

    <!-- CSS de Bootstrap -->
    <link href="<?php echo BASE_URL; ?>/Vista/Recursos/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/Vista/Recursos/css/styles.css" rel="stylesheet">

    <!-- JS -->
    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/bootstrap/bootstrap.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/md5.min.js"></script>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>/index.php">
                <img src="<?php echo BASE_URL; ?>/Vista/Media/Sitio/Logo/Logo2.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle">
                <h4 class="m-0 fw-bold">BIKE SHOP</h4>
            </a>

            <!-- Botón responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <?php echo $menuHtml; ?>
            </div>

            <!-- 🔹 ICONO DE CARRITO (abre el panel derecho) -->
            <?php if ($sesionValida) : ?>
                <button class="btn btn-light position-relative"
                        id="botonCarrito"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasCarrito"
                        style="display:none;">
                    🛒
                    <span id="contadorCarrito"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="display:none;">
                        0
                    </span>
                </button>
            <?php endif; ?>
        </div>
    </nav>
</header>


<!-- 🟦 PANEL DERECHO (OFFCANVAS) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCarrito">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Mi Carrito</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body" id="carritoContenido">
        <!-- Aquí se cargan los items del carrito -->
        <div class="text-center text-muted">Cargando...</div>
    </div>

    <div class="p-3">
        <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Carrito/Carrito.php" class="btn btn-primary w-100">
            Ir al carrito
        </a>
    </div>
</div>


<?php if ($sesionValida) : ?>
<script>
$(document).ready(function() {
    actualizarIconoCarrito();
});

// 🔹 ACTUALIZA EL ICONO DEL CARRITO
function actualizarIconoCarrito() {
    $.ajax({
        url: '<?php echo BASE_URL; ?>/Vista/Paginas/Carrito/Accion/ListarCarrito.php',
        method: 'POST',
        data: {
            idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0; ?>
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.length > 0) {
                let total = 0;
                respuesta.forEach(item => total += item.cicantidad);

                $('#contadorCarrito').text(total).show();
                $('#botonCarrito').show();

                cargarCarritoPanel();
            } else {
                $('#contadorCarrito').hide();
            }
        }
    });
}

// 🔹 CARGA EL PANEL DERECHO

function cargarCarritoPanel() {
    $.ajax({
        url: '<?php echo BASE_URL; ?>/Vista/Paginas/Carrito/Accion/ListarCarrito.php',
        method: 'POST',
        data: { idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0; ?> },
        dataType: 'json',
        success: function(respuesta) {

            if (respuesta.length === 0) {
                $("#carritoContenido").html(`
                    <div class="text-center text-muted">Tu carrito está vacío</div>
                `);
                return;
            }

            let html = "";
            respuesta.forEach(item => {
                html += `
                    <div class="border rounded p-2 mb-2 d-flex align-items-center">
                        <img src="${item.icon}" class="me-2" style="width:50px;height:50px;object-fit:cover;">
                        <div>
                            <strong>${item.pronombre}</strong><br>
                            Cantidad: ${item.cicantidad}<br>
                            Precio: $${item.proprecio}
                        </div>
                    </div>`;
            });

            $('#carritoContenido').html(html);
        }
    });
}

</script>
<?php endif; ?>
