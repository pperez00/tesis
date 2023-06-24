<?php
require_once('../libs/pdoconnect.php');
require_once('../libs/Usuario.php');
require_once('../libs/Limpiar_data.php');
require_once('../libs/Chat.php');
session_start();
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$chat = new Chat($id_usuario, $pdoconnect);
$error = false;
$enviar = array();
$datos = $_POST;
$error_premium = false;
$msj = '';
try {
    $id_chat_grupo = $chat->get_id_chat_grupo_session();
    if ($limpiar_data->validar_data($datos, -1) == true && strlen($id_chat_grupo) > 0) {
        $cantidad = $chat->get_cantidad_miembro_chat_grupo($datos['miembro']);
        if (intval($cantidad[0]['cantidad']) == 0) {
            $parametros = array();
            $parametros['where'] = "id_chat_grupo='" . $id_chat_grupo . "'";
            $parametros['tabla'] = 'grupos';
            $parametros['campos'] = 'count(*) as cantidad';
            $cantidad_miembros = $pdoconnect->buscar_datos($parametros);
            $parametros = array();
            if (intval($cantidad_miembros[0]['cantidad']) >= 3 && $usuario->get_premium() == 0) {
                $error = true;
                $error_premium = true;
                $msj = 'Tenes que ser premium';
            }
            if (count($chat->get_usuario_grupo($id_chat_grupo, $id_usuario)) > 0 && $error == false) {
                $id_usuario_fk =  $usuario->get_id_name($datos['miembro']);
                $parametros_buscar = array();
                $parametros_buscar['where'] = "id_chat_grupo='" . $id_chat_grupo . "' and miembro='" . $id_usuario_fk . "'";
                $parametros_buscar['tabla'] = 'grupos';
                $parametros_buscar['campos'] = 'count(*) as cantidad';
                if (intval($id_usuario_fk) > 0 && $id_usuario != $id_usuario_fk && intval($pdoconnect->buscar_datos($parametros_buscar)[0]['cantidad']) == 0) {
                    $datos['miembro'] = $id_usuario_fk;
                    $parametros['miembro'] = $datos['miembro'];
                    $parametros['id_chat_grupo'] = $id_chat_grupo;
                    $nombre_grupo = $chat->get_nombre($id_chat_grupo);
                    $parametros['nombre'] = $nombre_grupo[0]['nombre'];
                    $agregado = $chat->agregar_miembro($parametros);
                    if ($agregado == true) {
                        $msj = 'Ese usuario fue agregado';
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
                if ($error_premium == false) {
                    $msj = 'Error';
                }
            }
        } else {
            $error = true;
            $msj = 'Ese usuario ya estaba en el grupo';
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
