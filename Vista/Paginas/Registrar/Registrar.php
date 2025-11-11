<?php
include_once BASE_URL.'/configuracion.php';
include STRUCTURE_PATH.'/Header.php';

if ($sesion->validar()) { //Si ya tiene una sesion, redirige a Catalogo
    header('Location: '.BASE_URL.'/Vista/Paginas/Catalogo/Catalogo.php');
}
?>

<div class="">
    <div class="" style="max-width: 400px;">
<div class="">
    <div class="" style="max-width: 400px;">
        <h2 class="m-4">Register</h2>
        <form id="registerForm" class="">
            <input
                type="text"
                name="user"
                id="user"
                placeholder="Usuario"
                class=""
                style=";"
                required>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Email"
                class=""
                style="outline: none; box-shadow: none;"
                required>
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Contraseña"
                class=""
                style=""
                required>
            <button type="submit" class="">Registrarse</button>
        </form>
        <div class="">
            <div id="errorMessage" class=""></div>
            <div id="successMessage" class=""></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
                $('#successMessage').text('').removeClass('d-block').addClass('d-none');
                const formData = {
                    usnombre: $('#user').val(),
                    usmail: $('#email').val(),
                    uspass: md5($('#password').val()),
                    idrol: 3 //Crea Cliente por este medio, los demas roles los crea un admin
                };
                $.ajax({
                    url: 'Action/RegisterAction.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.trim() === 'success') {
                            $('#successMessage')
                                .text('Registro exitoso. Redirigiendo al login...')
                                .removeClass('d-none')
                                .addClass('d-block');
                            setTimeout(function() {
                                window.location.href = "<?php echo BASE_URL?>/View/Pages/Login/Login.php";
                            }, 2000);
                        } else {
                            $('#errorMessage')
                                .text(response)
                                .removeClass('d-none')
                                .addClass('d-block');
                        }
                    },
                    error: function() {
                        $('#errorMessage')
                            .text('Ocurrió un error al procesar la solicitud.')
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                });
            });
        });
    </script>
</div>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>