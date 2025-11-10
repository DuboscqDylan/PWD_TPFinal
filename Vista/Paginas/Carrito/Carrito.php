<?php
include_once "../../../configuracion.php";
include STRUCTURE_PATH . "/HeadSafe.php";
?>

<div class="bg-steam-lightgreen bdr-steam-nofocus w-75 mx-auto my-5">
    <div class="bdr-steam-nofocus px-5">
        <div class="m-3"> 
            <h1> Carro </h1>
        </div>
        <div class="w-100 m-auto flex-column">
            <div id='carrito' class="bg-steam-lightgray d-flex flex-column bdr-steam-focus" style="min-height: 100px;">
                <!-- Aqui se cargaran los nuevos divs con productos-->
            </div>
            <div class="d-flex flex-column align-items-end my-2">
                <button class="btn btn-success rounded-3 my-1" style="font-weight: bold; max-width: 150px" onclick="comprarCarrito();">Comprar</button>
                <button class="btn btn-danger rounded-3 my-1" style="font-weight: bold; max-width: 150px;" onclick="vaciarCarrito();">Vaciar carro</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ajax para listar los productos agregados al carrito (CompraItem)
    $(document).ready(function() {
        actualizarCarro();
    });

    function actualizarCarro() {
        var htmlContent = '';
        $.ajax({
            url: 'Action/ListarCarrito.php', // Ruta al script PHP que genera los datos
            method: 'POST',
            data: {
                idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado() : 0 ?>
            },
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                // Iterar sobre los compraItems y construir el HTML
                $.each(response, function(index, compraItem) {
                        htmlContent += `
                            <div class='d-flex flex-column p-2 bg-steam-lightgray' style="width: 100%; min-height: 100px;">
                                <div class="d-flex align-items-center justify-content-between text-center bg-steam-lightgreen bdr-steam-nofocus" style="height: fit-content;"> 
                                    <div class="d-flex">
                                        <img class="bg-steam-darkgreen bdr-steam-focus" style="margin: 5px; height: 100px; width: 100px;" src="${compraItem.icon}"> 

                                        </img>
                                        <div class="d-flex flex-column my-auto"> 
                                            <a class="text-decoration-none link-light" href="<?php echo BASE_URL ?>/View/Pages/Producto/Producto.php?idproducto=${compraItem.idproducto}" target="_blank">
                                                <p style='font-size: medium; font-weight: bold; margin: 10px; width: fit-content;'> ${compraItem.pronombre} </p>    
                                            </a>
                                            <p style='font-size: medium; font-weight: bold; margin: 10px; width: fit-content;'> Stock: ${compraItem.procantstock} </p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="ml-auto"> 
                                            <p style='font-size: medium; font-weight: bold; margin: 10px; width: fit-content;'> $ ${compraItem.proprecio} </p>
                                        </div>

                                        <div class="d-flex flex-column align-items-center mx-2" style="width: 25px;"> 
                                            <button class="d-flex flex-column align-items-center overflow-hidden p-0" style="height: 25px; width: 25px;" onclick="agregarItemCarrito(${compraItem.idproducto}, 1)"> 
                                                <p class="m-0 text-black">+</p> 
                                            </button>
                                            <input class="d-flex flex-column align-items-center overflow-hidden p-0 bg-steam-darkgreen text-center" style="height: 25px; width: 25px;" value="x${compraItem.cicantidad}" disabled>
                                            </input>
                                            <button class="d-flex flex-column align-items-center overflow-hidden p-0" style="height: 25px; width: 25px;" onclick="quitarItemCarrito(${compraItem.idproducto}, 1)"> 
                                                <p class="m-0 text-black">-</p> 
                                            </button>
                                        </div>    
                                    </div>
                                </div>
                            </div>`;
                });
                if (htmlContent == "") {
                    htmlContent = `<div class="bg-steam-lightgreen bdr-steam-nofocus px-3 m-auto"> <p class="my-2"> Carrito vacio </p> </div>`;
                }
                // Insertar el HTML generado en el contenedor
                $('#carrito').html(htmlContent);
            },
            error: function() {
                alert('Error al cargar el carrito.');
            }
        });
    }

    function agregarItemCarrito(idprod, cicant) {
        $.ajax({
            url: 'Action/SumarCicant.php',
            method: 'POST',
            data: {
                idproducto: idprod,
                idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra(): 0;?>, 
                cicantidad: cicant,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    actualizarCarro();
                }
                //alert(response.message);
            },
            error: function() {
                alert('Error al realizar operacion.');
            },
        });
    }

    function quitarItemCarrito(idprod, cicant) {
        $.ajax({
            url: 'Action/RestarCicant.php',
            method: 'POST',
            data: {
                idproducto: idprod,
                idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra(): 0;?>, 
                cicantidad: cicant,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    actualizarCarro();
                }
                //alert(response.message);
            },
            error: function() {
                alert('Error al realizar operacion.');
            },
        });
    }

    function vaciarCarrito() {
        $.ajax({
            url: 'Action/VaciarCarrito.php',
            method: 'POST',
            data: {
                idcompra: <?php echo $compraEstado ? $compraEstado->getObjCompra()->getIdcompra(): 0;?>,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    actualizarCarro();
                }
                alert(response.message);
            },
            error: function() {
                alert('Error al realizar operacion.');
            },
        });
    }

    function comprarCarrito() {
        if (confirm("¿Deseas realizar el pago?")) {
            $.ajax({
                url: 'Action/ComprarCarrito.php',
                method: 'POST',
                data: {
                    idcompraestado: <?php echo $compraEstado ? $compraEstado->getIdcompraestado(): 0;?>,
                    idnuevoestadotipo: 2 // Pasa a estado 2 (Aceptado) pues se confirma el pago automaticamente
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        window.location.href = "<?php echo BASE_URL?>/View/Pages/MisCompras/MisCompras.php";
                    }
                },
                error: function() {
                    alert('Error al realizar operacion.');
                },
            });
        } else {
            // El usuario hizo clic en "Cancelar"
            console.log("Compra cancelada.");
        }
    }


</script>

<?php include STRUCTURE_PATH . '/Foot.php'; ?>



