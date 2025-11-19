<?php
// Iniciar sesión solo si no existe una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegurar que $sesion y $sesionValida existen
// Ejemplo general (tú ya debes tener esto arriba en tu código):
// $sesion = new Sesion();
// $sesionValida = $sesion->validar();
?>

<footer class="bg-dark text-light py-3 mt-auto w-100" style="font-size: 0.9rem;">
    <div class="container">
        <div class="row align-items-center text-center text-md-start">

            <!-- Columna 1: Nombre y descripción -->
            <div class="col-md-4 mb-3 mb-md-0">
                <strong class="text-white">BIKE SHOP</strong><br>
                <span class="text-secondary">Programación Web Dinámica</span>
            </div>

            <!-- Columna 2: Enlaces -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex justify-content-center flex-wrap">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="text-light text-decoration-none mx-2">Inicio</a>
                    <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Catalogo/Catalogo.php" class="text-light text-decoration-none mx-2">Catálogo</a>

                    <!-- Mostrar SOLO si la sesión NO es válida -->
                    <?php if (!$sesionValida): ?>
                        <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Login/Login.php" class="text-light text-decoration-none mx-2">Login</a>
                        <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Registrar/Registrar.php" class="text-light text-decoration-none mx-2">Registro</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Columna 3: Redes -->
            <div class="col-md-4">
                <div class="d-flex justify-content-center justify-content-md-end">
                    <a href="mailto:contacto@bicicleta.com" class="text-light mx-2"><i class="bi bi-envelope-fill fs-5"></i></a>
                    <a href="#" class="text-light mx-2"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-light mx-2"><i class="bi bi-instagram fs-5"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-3">

        <div class="text-center text-secondary" style="font-size: 0.8rem;">
            © <?php echo date("Y"); ?> BIKE SHOP — Todos los derechos reservados.
        </div>
    </div>
</footer>