<?php
session_start();
require_once('../libs/pdoconnect.php');
require_once('../libs/admin.php');
require_once('../libs/Limpiar_data.php');
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Admin($pdoconnect);
$permiso = $usuario->get_permiso();
$id = $usuario->get_tabla_session_id();
$estado = $usuario->get_tabla($id);
$error = false;
$msj = '';
try {
    if ($estado == true && $permiso == 1) {
        $datos = $usuario->get_datos($id);
        $columnas = [];
        $data = [];
        foreach ($datos as $key => $value) {
            array_push($columnas, array_keys($value));
        }
        $enviar['data'] = $datos;
        $enviar['titulo'] = $usuario->get_tabla_name($usuario->get_tablas()[$id]);
        $enviar['columnas'] = array_unique($columnas);
    
    } else {
        $error = true;
        $msj = 'Error';
    }
} catch (\Throwable $th) {
    $error = true;
    $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
