<?php
require_once('../libs/pdoconnect.php');
require_once('../libs/limpiar_data.php');
require_once('../libs/Usuario.php');
session_start();
error_reporting(0);
$limpiar_data = new Limpiar_data();
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$datos = $_GET;
$enviar = array();
try {
    if ($permiso > 0) {
        $depurado = $limpiar_data->validar_data($datos,-1);
        if(isset($datos['status']) == true && $datos['status'] == 'pending' && $depurado == true){
            $id_usuario = $usuario->get_id();
            $payment_id = $datos['payment_id'];
            $estado = $datos['status'];
            $parametros = array();
            $data['estado'] = $estado;
            $data['id_usuario'] = $id_usuario;
            $data['id_mercado'] = $payment_id;
            $parametros['tabla'] = 'comprados';
            $parametros['values'] = $data;
            $pdoconnect->insertar($parametros);
            $parametros = array();
            $parametros['tabla'] = 'usuarios';
            $cambiar['premium'] = 1;
            $parametros['values'] = $cambiar;
            $parametros['where'] = "id='" . $id_usuario . "'";
            $pdoconnect->cambiar($parametros);
            $_SESSION['error'] = false;
            $_SESSION['mensaje_comprado'] = 'Cuenta cambiada a premium';
        } else {
            $_SESSION['error'] = true;
            $_SESSION['mensaje_comprado'] = 'Ocurrio un error';
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['mensaje_comprado'] = 'Ocurrio un error';
    }

} catch (\Throwable $th) {
    $_SESSION['error'] = true;
    $_SESSION['mensaje_comprado'] = 'Ocurrio un error';
}
header('Location:' . $pdoconnect->get_ruta() . 'index.php');
