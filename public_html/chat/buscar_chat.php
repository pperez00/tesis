<?php
session_start();
error_reporting(0);
require_once '../libs/pdoconnect.php';
require_once '../libs/Usuario.php';
require_once '../libs/Chat.php';
require_once  '../libs/Limpiar_data.php';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$chat = new Chat($usuario->get_id(), $pdoconnect);
$error = false;
$enviar = array();
$datos = array();
$msj = '';
$claves = array();
try {
    if ($usuario->get_permiso() > 0) {
        $chat->set_group_by('group by id_chat_grupo');
        $chats = $chat->buscar_chats();
        $msj = 'Chats cargados';
        $enviar['datos'] = $chats['datos'];
        $enviar['claves'] = $chats['claves'];
        $enviar['cantidad'] = count($chats['datos']);
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
