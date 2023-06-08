<?php
session_start();
require_once('../libs/pdoconnect.php');
require_once('../libs/admin.php');
require_once('../libs/Tarea.php');
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
    $tarea = new Tarea($pdoconnect);
    $tarea->set_permiso($permiso);
    
    
      $parametros['tabla'] = 'estado_tareas';
      $parametros['where'] = "id='" . $datos['Estado'] . "'";
      $parametros['campos'] = 'count(*) as cantidad';
      if (count($pdoconnect->buscar_datos($parametros)[0]['cantidad']) > 0) {
        $parametros['tabla'] = 'usuarios';
        $parametros['where'] = "id='" . $datos['usuario'] . "'";
        if (count($pdoconnect->buscar_datos($parametros)[0]['cantidad']) > 0) {
          if (isset($datos['id']) == false) {
            $tarea_creada = $tarea->insertar($datos);
            $msj = 'Se creo la tarea';
          } else {
            $id_tarea = $datos['id'];
            unset($datos['id']);
            $tarea_creada = $tarea->cambiar($datos, $id_tarea);
            $msj = 'Se actualizo la tarea';
          }
          if ($tarea_creada == false) {
            $error = true;
            $msj = 'No se pudo crear la tarea';
          }
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
