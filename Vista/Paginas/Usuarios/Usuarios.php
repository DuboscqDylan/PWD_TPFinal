<?php
include_once "../../../configuracion.php";
include STRUCTURE_PATH . "/HeadSafe.php"; 
?>

<div class="d-flex justify-content-center align-items-start gap-3">

    <!-- Tabla de Usuarios -->
    <div class="mt-5" style="max-width: 65%; padding: 20px;">
        <h1>Administrar Usuarios</h1>
        <table class="table table-bordered table-striped" id="usuariosTable" style="width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los usuarios serán cargados dinámicamente aquí -->
            </tbody>
        </table>
    </div>


    <!-- Formulario Estático para Alta de Usuario -->
    <div class=" mt-5" style="max-width: 45%; padding: 20px;">
        <h2>Alta de Usuario</h2>
        <form id="altaUsuarioForm">
            <div class="form-group">
                <label for="user" class="text-white">Nombre</label>
                <input type="text" class="form-control" id="user" name="user" required>
            </div>
            <div class="form-group">
                <label for="email" class="text-white">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password" class="text-white">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="rol" class="text-white">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                    <option value="">Seleccionar...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Deposito">Deposito</option>
                    <option value="Cliente">Cliente</option>
                    <!-- Otros roles pueden ser añadidos aquí -->
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Crear Usuario</button>
        </form>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div id="errorMessage" class="text text-center mt-3 p-2 bg-danger rounded-3 w-100 text-white d-none"></div>
            <div id="successMessage" class="text text-center mt-3 p-2 bg-success rounded-3 w-100 text-white d-none"></div>
        </div>

    </div>

    <!-- Formulario Estático para Modificar Usuario -->
    <div class=" mt-5" style="max-width: 45%; padding: 20px;">
        <h2>Modificar Usuario</h2>
        <form id="modificarUsuarioForm">
        <div class="form-group">
                    <!--  SEGUIMOS DESDE ACA, hay que actualizar el ajax y en modificacionUsuario tener en cuenta el ABMUsuarioRol  -->
                <label for="usuarioID" class="text-white">Id</label>
                <input type="text" class="form-control" id="usuarioID" name="usuarioID" required>
            </div>
            <div class="form-group">
                <label for="modNombre" class="text-white">Nombre</label>
                <input type="text" class="form-control" id="modNombre" name="modNombre" >
            </div>
            <div class="form-group">
                <label for="modEmail" class="text-white">Email</label>
                <input type="email" class="form-control" id="modEmail" name="modEmail" >
            </div>
            <div class="form-group">
                <label for="modRol" class="text-white">Rol</label>
                <select class="form-control" id="modRol" name="modRol" >
                    <option value="">Seleccionar...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Deposito">Deposito</option>
                    <option value="Cliente">Cliente</option>
                    <!-- Otros roles pueden ser añadidos aquí -->
                </select>
            </div>
            <input type="hidden" id="modUserId">
            <button type="submit" class="btn btn-primary mt-3">Actualizar usuario</button>
        </form>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div id="errorMessageMod" class="text text-center mt-3 p-2 bg-danger rounded-3 w-100 text-white d-none"></div>
            <div id="successMessageMod" class="text text-center mt-3 p-2 bg-success rounded-3 w-100 text-white d-none"></div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function() {
        cargarUsuarios(); // Funcion que es llamada al cargar la página, y contiene la llamada AJAX para cargar los usuarios

        function cargarUsuarios() {
            $.ajax({
                url: 'Action/ListarUsuarios.php',
                method: 'POST',
                data: {
                    todo: true
                },
                dataType: 'json',
                success: function(response) {
                    var tableContent = '';
                    $.each(response, function(index, usuario) {
                        tableContent += `
                        <tr id="usuario-${usuario.idusuario}">
                            <td>${usuario.idusuario}</td>
                            <td>${usuario.usnombre}</td>
                            <td>${usuario.usmail}</td>
                            <td>${usuario.usdeshabilitado} </td>
                            <td>${usuario.rol}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="bajaUsuario(${usuario.idusuario}, '${usuario.rol}')">Baja</button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#usuariosTable tbody').html(tableContent);
                },
                error: function() {
                    alert('Error al cargar los usuarios.');
                }
            });
        }

        function modificarUsuario(id) {
            // Cargar la información del usuario a modificar
            $.ajax({
                url: 'Action/ListarUsuarios.php',
                method: 'POST',
                data: {
                    idusuario: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        const usuario = response[0];
                        $('#modUserId').val(usuario.idusuario);
                        $('#modNombre').val(usuario.usnombre);
                        $('#modEmail').val(usuario.usmail);
                        $('#modRol').val(usuario.rol);
                        $('#modificarUsuarioForm').show();
                    }
                },
                error: function() {
                    alert('Error al cargar la información del usuario.');
                }
            });
        }

        // Manejo de la baja de un usuario
        window.bajaUsuario = function(id, rol) {
            if (confirm('¿Estás seguro de dar de baja este usuario?')) {
                $.ajax({
                    url: 'Action/BajaUsuarios.php',
                    method: 'POST',
                    data: {
                        idusuario: id,
                        rol: rol
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            alert(response.message);
                            // Eliminar la fila de la tabla
                            $('#usuario-' + id).remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error al procesar la solicitud de baja.');
                    }
                });
            }
        }

        // Alta de usuario
        $('#altaUsuarioForm').submit(function(e) {
            e.preventDefault();
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');
            pass = $('#password').val()
            passMD5 = md5(pass)
            const formData = {
                user: $('#user').val(),
                email: $('#email').val(),
                password: passMD5,
                rol: $('#rol').val()
            };

            $.ajax({
                url: 'Action/AltaUsuario.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.trim() === 'success') {
                        $('#errorMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#successMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarUsuarios(); // Recargar la lista de usuarios
                    } else {
                        $('#successMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#errorMessage')
                            .text(response)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#successMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-block')
                            .addClass('d-none');
                    $('#errorMessage')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
                
        });


        // Modificación de usuario
       $('#modificarUsuarioForm').submit(function(e) {
            e.preventDefault();
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');
            
            var formData = {
                usuarioID: parseInt($('#usuarioID').val(), 10),
            }
            if ($('#modNombre').val().trim() != "") {
                formData['modNombre'] = $('#modNombre').val();
            }
            if ($('#modEmail').val().trim() != "") {
                formData['modEmail'] = $('#modEmail').val();
            }
            if ($('#modRol').val().trim() != "") {
                formData['modRol'] = $('#modRol').val();
            }

            console.log(formData);
            $.ajax({
                url: 'Action/ModificacionUsuario.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var res = JSON.parse(response);
                    console.log(res);
                    if (res.success) {
                        $('#errorMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarUsuarios(); // Recargar la lista de usuarios
                    } else {
                        $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                        cargarUsuarios();
                        $('#errorMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                    $('#errorMessageMod')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
        });

    });
</script>

<?php include STRUCTURE_PATH . "/Foot.php"; ?>