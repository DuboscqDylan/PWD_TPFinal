<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';

if ($session->validar()) { //Si ya tiene una sesion, redirige a Catalogo
    header('Location: '.BASE_URL.'/index.php');
}
?>

<div class="">
    <div class="" style="max-width: 400px;">
        <h2 class="m-4">Login</h2>
        <form id="loginForm" method="POST" class="">
            <input type="text" name="user" id="user" placeholder="Usuario" class="border-0 border-bottom p-2 bg-transparent text-white" style="outline: none; box-shadow: none;" required>
            <input type="password" name="password" id="password" placeholder="Contraseña" class="border-0 border-bottom p-2 bg-transparent text-white" style="outline: none; box-shadow: none;" required>
            <button type="submit" class="btn btn-primary btn-steam">Ingresar</a> </button>
        </form>
        <div id="messageContainer" class="">

        </div>
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
                url: 'Action/LoginAction.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    if (response) {
                        messageContainer.addClass('bg-success').text('Inicio de sesión exitoso. Redirigiendo...');
                        setTimeout(function() {
                            window.location.href = "<?php echo BASE_URL?>/index.php";
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