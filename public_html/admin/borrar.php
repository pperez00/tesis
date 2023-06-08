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
$id = $_GET['id'];
$error = false;
try {
$msj = '';
if ($permiso == 1 && $estado == true){
      $borrado = $usuario->borrar($id);
      if($borrado == true){
          $msj = 'Fila borrada';
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
