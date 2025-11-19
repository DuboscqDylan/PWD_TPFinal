<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';
$alerta = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === "" || $email === "" || $mensaje === "") {
        $alerta = "<div class='alert alert-danger mt-3'>⚠ Todos los campos son obligatorios.</div>";
    } else {

        $alerta = "<div class='alert alert-success mt-3'>✔ Tu mensaje fue enviado correctamente.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contacto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f0f0;
        }

        .card-contacto {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card-contacto">
                    <h2 class="text-center mb-4">Contacto</h2>

                    <form action="" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea name="mensaje" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                            Enviar mensaje
                        </button>
                    </form>

                    <!-- Resultado -->
                    <?php if ($alerta) echo $alerta; ?>

                </div>

            </div>
        </div>
    </div>

</body>

</html>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>