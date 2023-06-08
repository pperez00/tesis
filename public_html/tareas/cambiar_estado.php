<?php
require_once('../libs/pdoconnect.php');
require_once('../libs/Usuario.php');
require_once('../libs/Limpiar_data.php');
require_once('../libs/Tarea.php');
require_once('../libs/Chat.php');
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
    if ($permiso > 0) {
        $tarea = new Tarea($pdoconnect);
        $chat = new Chat($id_usuario, $pdoconnect);
        $estado = intval($datos['estado']);
  
        $validar_session = $chat->get_datos_validar_session();
      
        $key = array_search($estado, array_column($tarea->get_estados(), 'id'));
        if ($key === false) {
            $error = true;
            $msj = 'Error';
        }
        if ($error == false && is_array($validar_session)) {
            $tarea->set_id_grupo($validar_session['id_grupo']);
            $tarea->set_id_usuario($validar_session['id_usuario']);
            $tareas_id_usuario = $tarea->get_tareas_id_usuario_grupo($validar_session['id_usuario'], $validar_session['id_grupo']);
             if($error == false){
                $cambiar = $tarea->cambiar_estado_tarea($tareas_id_usuario[0]['id'], $estado);
                if ($cambiar == true) {
                    $msj = 'Estado cambiado';
                } else {
                    $error = true;
                    $msj = 'Error';
                }
             }
            } else {
                $error = true;
                $msj = 'Error';
            }
        }  else {
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
