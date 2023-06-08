<?php
require_once('../libs/pdoconnect.php');
require_once('../libs/Usuario.php');
require_once('../libs/Limpiar_data.php');
require_once('../libs/Tarea.php');
session_start();
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$datos = $_POST;
$error = false;
$msj = '';
try {
  if ($permiso == 2 or $permiso == 1) {
    if ($limpiar_data->validar_data($datos, -1)) {
      $tarea = new Tarea($pdoconnect);
      $tarea->set_permiso($permiso);
      $datos['usuario'] = $tarea->get_id_usuario();
      $datos['id_grupo'] = $tarea->get_id_grupo();
      $datos['estado'] = 4;
      $tarea_creada = $tarea->insertar($datos);
      if ($tarea_creada == true) {
        $msj = 'Se creo la tarea';
        unset($_SESSION['datos_validar']);
      } else {
        $error = true;
        $msj = 'No se pudo crear la tarea';
      }
    } else {
      $error = true;
      $msj = 'Error';
    }
  } else {
    $error = true;
    $msj = 'No tenes suficientes permisos para eso';
  }
} catch (\Throwable $th) {
  $error = true;
  $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
