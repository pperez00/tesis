<?php
error_reporting(0);
session_start();
require_once '../libs/pdoconnect.php';
require_once '../libs/Limpiar_data.php';
$datos = $_POST;
$enviar = array();
$error = false;
$msj = '';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);

try {
    if ($limpiar_data->validar_data($datos, -1) == true) {

        $parametros = array();
        $parametros['tabla'] = 'usuarios';
        $parametros['campos'] = 'pass,id';
        $parametros['where'] = "usuario='" . $datos['usuario'] . "'";
        $hash = $pdoconnect->buscar_datos($parametros)[0];
        if ($hash != null) {
            if (password_verify($datos['pass'], $hash['pass'])) {
                $datos_usuario = array();
                $datos_usuario['id'] = $hash['id'];
                $datos_usuario['usuario'] = $datos['usuario'];
                $_SESSION['usuario'] = $datos_usuario;
                $msj = 'Bienvenido ' . ucfirst($datos['usuario']);
            } else {
                $error = true;
                $msj = 'Error';
            }
        } else {
            $error = true;
            $msj = 'Error';
        }
    } else {
        $error = true;
        $msj = 'Los datos estan vacios';
    }
} catch (\Throwable $th) {
    $error = true;
    $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;
header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
