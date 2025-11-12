<footer class="bg-dark text-light py-3 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <!-- Nombre y descripción breve -->
        <div class="text-center text-md-start mb-2 mb-md-0">
            <strong>Bici-cleta</strong> | Programación Web Dinámica
        </div>

        <!-- Enlaces -->
        <div class="d-flex flex-wrap justify-content-center mb-2 mb-md-0">
            <a href="<?php echo BASE_URL; ?>/index.php" class="text-light text-decoration-none mx-2 small">Inicio</a>
            <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Catalogo/Catalogo.php" class="text-light text-decoration-none mx-2 small">Catálogo</a>
            <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Login/Login.php" class="text-light text-decoration-none mx-2 small">Login</a>
            <a href="<?php echo BASE_URL; ?>/Vista/Paginas/Registrar/Registrar.php" class="text-light text-decoration-none mx-2 small">Registro</a>
        </div>

        <!-- Contacto / Redes -->
        <div class="text-center text-md-end">
            <a href="mailto:contacto@bicicleta.com" class="text-light mx-2"><i class="bi bi-envelope-fill"></i></a>
            <a href="#" class="text-light mx-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-light mx-2"><i class="bi bi-instagram"></i></a>
        </div>
    </div>

    <!-- Línea divisoria y derechos -->
    <div class="text-center text-secondary small mt-2">
        © <?php echo date("Y"); ?> Bici-cleta — Todos los derechos reservados.
    </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/Validaciones.js"></script>
</body>
</html>
