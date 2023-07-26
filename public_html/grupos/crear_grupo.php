<?php
require_once('../libs/pdoconnect.php');
require_once('../libs/Usuario.php');
require_once('../libs/Limpiar_data.php');
require_once('../libs/Grupo.php');
session_start();
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$error = false;
$enviar = array();
$datos = $_POST;
$msj = '';
try {
    if ($limpiar_data->validar_data($datos, -1) == true && intval($id_usuario) > 0) {
        $parametros = array();
        $parametros['where'] = "nombre='" . $datos['nombre'] . "'";
        $parametros['tabla'] = 'grupos';
        $parametros['campos'] = 'count(*) as cantidad';
        if ($grupo->get_grupo(" and nombre='" . $datos['nombre'] . "'") == null && intval($pdoconnect->buscar_datos($parametros)[0]['cantidad']) == 0) {
            $datos['usuario'] = $id_usuario;
            $id_usuario_fk =  $usuario->get_id_name($datos['miembro']);
            $datos['miembro'] = $id_usuario;
            if ($usuario->get_premium() == 0 && count($grupo->get_grupos()) >= 3) {
                $error = true;
                $msj = 'No se puede crear el grupo, necesitas mas espacio';
            }
            if ($error == false) {
                $insertar = $grupo->insertar($datos);
                if ($insertar == true) {
                    $msj = 'Se creo el grupo';
                } else {
                    $error = true;
                    $msj = 'No se pudo crear el grupo';
                }
            }
        } else {
            $error = true;
            $msj = 'Ya existe un grupo con ese nombre';
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
