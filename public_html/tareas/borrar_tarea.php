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
$datos = $_GET;
$error = false;
$msj = '';
try {
    if ($permiso == 2 or $permiso == 1) {
        $id_usuario = $usuario->get_id();
        $chat = new Chat($id_usuario, $pdoconnect);
        if ($limpiar_data->validar_data($datos, -1) == true) {
            $datos_validados = $chat->get_usuario_grupo_cantidad($datos['id']);
            if ($datos_validados['estado'] == true) {
                $tarea = new Tarea($pdoconnect);
                $tareas_id_usuario = $tarea->get_tareas_id_usuario_grupo($datos_validados['id_usuario'], $datos_validados['id_grupo']);
                $tarea->set_permiso($permiso);
                $borrar = $tarea->borrar_tarea($tareas_id_usuario[0]['id']);
                if($borrar == true){
                    $msj = 'Tarea borrada';
                } else {
                    $error = true;
                    $msj = 'No se pudo borrar la tarea';
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
