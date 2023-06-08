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
$id_tabla = $usuario->get_tabla_session_id();
$estado = $usuario->get_tabla($id_tabla);
$datos = $_POST;
$error = false;
try {
  $msj = '';
  if ($permiso == 1 && $estado == true && $limpiar_data->validar_data($datos, -1) == true) {
    if (intval($usuario->get_informacion_id($datos['id_usuario'], 'count(*)')) > 0) {
      if (isset($datos['id']) == true) {
        $id_dato = $datos['id'];
        unset($datos['id']);
        $parametros = array();
        $parametros['tabla'] = $usuario->get_tablas()[$id_tabla];
        $parametros['values'] = $datos;
        $parametros['where'] = "id='" . $id_dato . "'";
        $insertado = $pdoconnect->cambiar($parametros);
      } else {
        $parametros = array();
        $parametros['tabla'] = $usuario->get_tablas()[$id_tabla];
        $parametros['values'] = $datos;
        $insertado = $pdoconnect->insertar($parametros);
      }
      if ($insertado == true) {
        $msj = 'Tabla actualizada';
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
    $msj = 'No se encontro al usuario';
  }
} catch (\Throwable $th) {
  $error = true;
  $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
