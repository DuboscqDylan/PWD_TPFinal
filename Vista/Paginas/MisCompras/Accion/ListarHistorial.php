<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$abmCompra = new AbmCompra();
$abmCompraEstado = new AbmCompraEstado();
$abmUsuario = new AbmUsuario();

$usuario = $abmUsuario->buscar(['idusuario' => $data['idusuario']])[0];
$compras = $abmCompra->buscar(['usuario' => $usuario]);

$historial = [];

foreach ($compras as $compra) {
    $estadosFormateados = [];
    $compraEstados = $abmCompraEstado->buscar(['objCompra' => $compra]); // TODOS los estados de la compra

    foreach ($compraEstados as $ce) {
        $estadosFormateados[] = [
            'idcompraestado' => $ce->getIdcompraestado(),
            'estado'         => $ce->getObjCompraEstadoTipo()->getCetdescripcion(),
            'idestadotipo'   => $ce->getObjCompraEstadoTipo()->getIdcompraestadotipo(),
            'fechaini'       => $ce->getCefechaini(),
            'fechafin'       => $ce->getCefechafin()
        ];
    }

    $historial[] = [
        'idcompra' => $compra->getIdcompra(),
        'cofecha'  => $compra->getCofecha(),
        'estados'  => $estadosFormateados
    ];
}

echo json_encode($historial);
 /*
$abmCompra = new AbmCompra();
$abmCompraEstado = new AbmCompraEstado();
$abmUsuario = new AbmUsuario();

$idusuario = $data['idusuario'];
$usuario = $abmUsuario->buscar(['idusuario' => $idusuario])[0];

$compras = $abmCompra->buscar(['usuario' => $usuario]);

$historial = [];

foreach ($compras as $compra) {
    $estadosFormateados[] = [
        'idcompraestado' => $compra->getIdcompraestado(),
        'estado'        => $compra->getObjCompraEstadoTipo()->getCetdescripcion(),
        'idestadotipo'  => $compra->getObjCompraEstadoTipo()->getIdcompraestadotipo(),
        'fechaini'      => $compra->getCefechaini(),
        'fechafin'      => $compra->getCefechafin(),
    ];
}

$historial[] = [
    'idcompra' => $compra->getIdcompra(),
    'cofecha'  => $compra->getCofecha(),
    'estados'  => $estadosFormateados
];


echo json_encode($historial);
*/