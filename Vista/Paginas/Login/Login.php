<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';
if ($sesion->validar()) {
    header('Location: ' . BASE_URL . '/index.php');
}
?>

<!-- Contenedor principal centrado -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4 bg-primary text-white" style="max-width: 400px; width: 100%; border-radius: 10px;">
        <h2 class="text-center mb-4 fw-bold">Iniciar Sesión</h2>

        <form id="loginForm" method="POST" class="d-flex flex-column gap-3">
            <input type="text"
                name="user"
                id="user"
                placeholder="Usuario"
                class="form-control border-0 border-bottom bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>

            <input type="password"
                name="password"
                id="password"
                placeholder="Contraseña"
                class="form-control border-0 border-bottom bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>

            <button type="submit" class="btn btn-light text-primary fw-bold w-100 mt-3">
                Ingresar
            </button>
        </form>

        <div id="messageContainer" class="mt-3 text-center fw-semibold rounded p-2 d-none"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                usnombre: $('#user').val(),
                uspass: md5($('#password').val())
            };

            const messageContainer = $('#messageContainer');
            messageContainer.removeClass('d-none bg-danger bg-success').text('');

            $.ajax({
                url: '<?php echo BASE_URL; ?>/Vista/Paginas/Login/Accion/LoginAction.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    if (response) {
                        messageContainer.addClass('bg-success').text('Inicio de sesión exitoso. Redirigiendo...');
                        setTimeout(function() {
                            window.location.href = "<?php echo BASE_URL ?>/index.php";
                        }, 2000);
                    } else {
                        messageContainer.addClass('bg-danger').text('Datos incorrectos.');
                    }
                },
                error: function() {
                    messageContainer.addClass('bg-danger').text('Ocurrió un error al procesar la solicitud.');
                }
            });
        });
    });
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>