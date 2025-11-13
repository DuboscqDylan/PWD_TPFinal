<?php
include_once 'configuracion.php';
include STRUCTURE_PATH . '/Header.php';
//prueba para pshear img
?>

<!-- Contenido principal -->
<div class="container text-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <div class="card-body">
            <img src="Vista/Media/sitio/Logo/Logo.png" alt="Logo del sitio" class="mb-4" height="120">

            <h1 class="fw-bold text-primary mb-3">Trabajo Práctico Final</h1>
            <h3 class="text-secondary mb-4">Programación Web Dinámica</h3>

            <p class="lead text-muted mb-4">
                Bienvenido al sistema desarrollado para la materia <strong>Programación Web Dinámica (PWD)</strong>.<br>
                Desde aquí podés acceder a las distintas secciones del sitio.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="Vista/Paginas/Login/login.php" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                    Iniciar sesión
                </a>
                <a href="Vista/Paginas/Registrar/registrar.php" class="btn btn-outline-primary px-4 py-2 rounded-pill shadow-sm">
                    Registrarse
                </a>
                <a href="Vista/Paginas/Compras/Compras.php" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                    Ver compras
                </a>
                <a href="Vista/Paginas/Contacto/contacto.php" class="btn btn-warning px-4 py-2 rounded-pill shadow-sm">
                    Contacto
                </a>
            </div>
        </div>
    </div>
</div>


<?php include STRUCTURE_PATH . '/Footer.php'; ?>
