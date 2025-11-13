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
        <!-- Navbar principal -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>/index.php">
                    <img src="<?php echo (BASE_URL); ?>/Vista/Media/Sitio/Logo/Logo2.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle">
                    <h4 class="m-0 fw-bold">BIKE SHOP</h4>
                </a>

                <!-- Botón responsive -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú dinámico -->
                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <?php echo $menuHtml; ?>
                </div>
            </div>
        </nav>
    </header>

<?php if ($sesionValida) : ?>
<script>
$(document).ready(function() {
    actualizarIconoCarrito();
});

function actualizarIconoCarrito() {
    $.ajax({
        url: '<?php echo BASE_URL; ?>/Vista/Paginas/Carrito/Accion/ListarCarrito.php',
        method: 'POST',
        data: { idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0; ?> },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.length > 0) {
                let total = 0;
                respuesta.forEach(item => { total += item.cicantidad; });
                $('#contadorCarrito').text(total);
                $('#contadorCarrito').show();
            } else {
                $('#contadorCarrito').hide();
            }
        }
    });
}
</script>
<?php endif; ?>