<?php
include_once '../../../configuracion.php';
include STRUCTURE_PATH.'/Head.php';

if ($sesion->validar()) { //Si ya tiene una sesion, redirige a Catalogo
    header('Location: '.BASE_URL.'/View/Pages/Catalogo/Catalogo.php');
}
?>

<div class="container my-auto ">
    <div class="text-center p-2 bg-steam-lightgreen bdr-steam-nofocus mx-auto rounded-4" style="max-width: 400px;">
        <h2 class="m-4">Register</h2>
        <form id="registerForm" class="bg-steam-darkgreen bdr-steam-focus rounded-4 m-4 d-flex flex-column justify-content-center align-items-center p-5 gap-5">
            <input
                type="text"
                name="user"
                id="user"
                placeholder="Usuario"
                class="border-0 border-bottom p-2 bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Email"
                class="border-0 border-bottom p-2 bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Contraseña"
                class="border-0 border-bottom p-2 bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>
            <button type="submit" class="btn btn-primary btn-steam">Registrarse</button>
        </form>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div id="errorMessage" class="text text-center mt-3 p-2 bg-danger rounded-3 w-100 text-white d-none"></div>
            <div id="successMessage" class="text text-center mt-3 p-2 bg-success rounded-3 w-100 text-white d-none"></div>
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

<?php include STRUCTURE_PATH . '/Foot.php'; ?>