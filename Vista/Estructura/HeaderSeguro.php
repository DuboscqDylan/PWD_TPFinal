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

    if (!$sesion->validarPagina($menues)) {
       header("Location: ".BASE_URL."/Vista/Paginas/SesionInvalida/SesionInvalida.php");
       exit();
    }
} else {
    header("Location: " . BASE_URL . "/Vista/Paginas/SesionInvalida/SesionInvalida.php");
    exit();
}

$menuHtml = "<div class='d-flex align-items-center ms-3'>";
$menuHtml .= "<a class='nav-link text-white fw-semibold me-3' href='" . BASE_URL . "/Vista/Paginas/Catalogo/Catalogo.php'>Catálogo</a>";

foreach ($menues as $menu) {
    if ($menu->getPadre() != null) {
        $menuHtml .= "<a class='nav-link text-white me-3' href='" . BASE_URL . $menu->getMeurl() . "'>" . $menu->getMenombre() . "</a>";
    }
}

if ($sesionValida) {
    $menuHtml .= "
        <a class='nav-link text-white me-3 fw-semibold' href='" . BASE_URL . "/Vista/Paginas/Perfil/Perfil.php'>
            " . $usuario->getUsnombre() . "
        </a>
        <a class='nav-link text-white fw-semibold' href='" . BASE_URL . "/Vista/Paginas/Logout/Logout.php'>Salir</a>
    ";
} else {
    $menuHtml .= "
        <a class='nav-link text-white me-3' href='" . BASE_URL . "/Vista/Paginas/Login/Login.php'>Login</a>
        <a class='nav-link text-white' href='" . BASE_URL . "/Vista/Paginas/Registro/Registro.php'>Registro</a>
    ";
}

$menuHtml .= "</div>";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bike Shop</title>

    <link href="<?php echo BASE_URL; ?>/Vista/Recursos/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/Vista/Recursos/css/styles.css" rel="stylesheet">

    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/bootstrap/bootstrap.bundle.js"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container-fluid">

                <!-- Marca -->
                <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>/index.php">
                    <img src="<?php echo BASE_URL; ?>/Vista/Media/Sitio/Logo/Logo2.png" height="45" class="me-2 rounded-circle" alt="Logo">
                    <h4 class="mb-0 text-white fw-bold">Bike Shop</h4>
                </a>

                <!-- Botón hamburguesa -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                    aria-controls="menuPrincipal" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú -->
                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <?php echo $menuHtml; ?>
                </div>

                <!-- Carrito -->
                <?php if ($sesionValida) : ?>
                    <button class="btn btn-light position-relative ms-2"
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

    <script>
        // Al cargar la página, actualizamos el contador del carrito
        $(document).ready(function() {
            actualizarIconoCarrito();
        });

        // Actualiza el ícono del carrito con la cantidad de productos
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
                        respuesta.forEach(item => {
                            total += item.cicantidad;
                        });
                        $('#contadorCarrito').text(total);
                        $('#botonCarrito').fadeIn();
                    } else {
                        $('#botonCarrito').fadeOut();
                    }
                }
            });
        }
    </script>