<?php
session_start();
require_once('../libs/pdoconnect.php');
require_once('../libs/admin.php');
require_once('../libs/Grupo.php');
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
        $grupo = new Grupo($usuario->get_id(), $pdoconnect);
        $grupo->set_permiso($permiso);
        $parametros['campos'] = 'count(*) as cantidad';
        $parametros['tabla'] = 'usuarios';
        $parametros['where'] = "id='" . $datos['usuario'] . "'";
        if (intval($pdoconnect->buscar_datos($parametros)[0]['cantidad']) > 0) {
            $parametros['where'] = "id='" . $datos['miembro'] . "'";
            if (intval($pdoconnect->buscar_datos($parametros)[0]['cantidad']) > 0) {
                if (isset($datos['id']) == false) {
                    $grupo_creado = $grupo->insertar($datos);
                    $msj = 'Se creo el grupo';
                } else if (count($grupo->get_usuario_grupo($datos['id_chat_grupo'], $datos['usuario'])) > 0) {
                    $id_grupo = $datos['id'];
                    unset($datos['id']);
                    $grupo_creado = $grupo->cambiar($datos, $id_grupo);
                    $msj = 'Se actualizo el grupo';
                } else {
                    $error = true;
                    $msj = 'Error';
                }
                if ($grupo_creado == false) {
                    $error = true;
                    $msj = 'No se pudo crear el grupo';
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
    $msj = 'Error 2';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
