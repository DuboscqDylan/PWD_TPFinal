<footer class="bg-dark text-light pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Columna 1: descripción -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold text-uppercase mb-3">Bici-cleta</h5>
                <p class="text-secondary">
                    Sistema desarrollado para el Trabajo Práctico Final de la cátedra
                    <strong>Programación Web Dinámica</strong>.  
                    Proyecto académico 2024 – Facultad de Informática.
                </p>
            </div>

            <!-- Columna 2: enlaces -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold text-uppercase mb-3">Enlaces rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/index.php" class="text-light text-decoration-none">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/Vista/Paginas/Catalogo/Catalogo.php" class="text-light text-decoration-none">Catálogo</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/Vista/Paginas/Login/Login.php" class="text-light text-decoration-none">Iniciar Sesión</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/Vista/Paginas/Registrar/Registrar.php" class="text-light text-decoration-none">Registrarse</a></li>
                </ul>
            </div>

            <!-- Columna 3: contacto -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold text-uppercase mb-3">Contacto</h5>
                <ul class="list-unstyled">
                    <li><i class="bi bi-envelope-fill me-2"></i> contacto@bicicleta.com</li>
                    <li><i class="bi bi-geo-alt-fill me-2"></i> Buenos Aires, Argentina</li>
                    <li><i class="bi bi-telephone-fill me-2"></i> +54 11 5555-5555</li>
                </ul>

                <div class="mt-3">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-twitter-x fs-4"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <!-- Derechos -->
        <div class="text-center text-secondary small">
            © <?php echo date("Y"); ?> Bici-cleta — Todos los derechos reservados.
        </div>
    </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/Validaciones.js"></script>
</body>
</html>
