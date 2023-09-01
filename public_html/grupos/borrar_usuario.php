<?php
$error = false;
$enviar = array();
$msj = '';
try {
    require_once('../libs/pdoconnect.php');
    require_once('../libs/Usuario.php');
    require_once('../libs/Grupo.php');
    require_once('../libs/Limpiar_data.php');
    require_once('../libs/Tarea.php');
    session_start();
    error_reporting(1);
    $limpiar_data = new Limpiar_data;
    $pdoconnect = new Pdoconnect($limpiar_data);
    $usuario = new Usuario($pdoconnect);
    $permiso = $usuario->get_permiso();
    $usuario->es_valido();
    $tarea = new Tarea($pdoconnect);
    $id = $usuario->get_id();
    $dato = $_GET['id'];
    $grupo = new Grupo($id, $pdoconnect);
    $datos = array();
    $datos[0] = $dato;

    if ($limpiar_data->validar_data($datos, -1) == true) {
        $separar = explode('_', $dato);
        $id_chat = $separar[0];
        $miembro = intval($separar[1]);
        $grupo_array = $grupo->get_grupo(" or id_chat_grupo='" . $id_chat . "' and miembro='" . $miembro . "'");
        if ($grupo_array != null && count($tarea->get_tareas_id_usuario_grupo($miembro, $id_chat)) == 0) {
            $vacio = empty($grupo->get_usuario_grupo($id_chat, $id, $miembro));
            if ($vacio == false) {
                $borrar = $grupo->borrar_miembro($miembro, $id_chat);
                if ($borrar['borrado'] == true) {
                    $enviar['cantidad'] = count($grupo_array) - 1;
                    $enviar['borrado'] = $borrar['borrado'];
                    $msj = 'Miembro borrado';
                } else {
                    $error = true;
                    $msj = 'Error';
                    if(isset($borrar['msj'])){
                        $msj = $borrar['msj'];
                    }
                    $enviar = $borrar;
                    
                }
            } else {
                $error = true;
                $msj = 'Error';
            }
        } else {
            $error = true;
            $msj = 'Tiene tareas pendientes';
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
