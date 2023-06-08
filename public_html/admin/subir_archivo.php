<?php
error_reporting(0);
session_start();
require_once '../libs/pdoconnect.php';
require_once '../libs/Limpiar_data.php';
require_once('../libs/Grupo.php');
require_once '../libs/Usuario.php';
require_once('../libs/Archivo.php');
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$datos = $_POST;
$enviar = array();
$error = false;
$parametros = array();
$msj = '';
try {
    if ($limpiar_data->validar_data($datos, -1) == true && $permiso == 1) {
        $id_usuario = intval($datos['usuario']);
        $grupo = new Grupo($id_usuario, $pdoconnect);
        $archivo = new Archivo($id_usuario, $pdoconnect);
        $carpetas = $usuario->get_carpetas();
        $usuario_carpeta_principal = '../usuarios/' . $usuario->get_informacion_id($id_usuario,'usuario');
        $target_dir = $usuario_carpeta_principal . '/' . $carpetas[1]  . '/';
        $nombre = basename($_FILES["archivo"]["name"]);
        $target_file = $target_dir . $nombre;
        $$extension_pdf = end((explode(".", $nombre)));

        if (count($grupo->get_grupo_sin_usuario(" id_chat_grupo='" . $datos['grupo'] . "'")) == 0) {
            $error = true;
            $msj = 'El grupo no existe';
        }

        if ($_FILES["archivo"]["size"] > 10500000) {
            $error = true;
            $msj = 'El archivo es muy grande';
        }
        if ($$extension_pdf != "pdf") {
            $error = true;
            $msj = 'El archivo es una extension invalida';
        }

        if ($error == false) {
            if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
                $parametros['nombre'] = $nombre;
                $parametros['grupo'] = $datos['grupo'];

                if(isset($datos['id']) == false){
                    $archivo->set_id_usuario($id_usuario);
                    $insertado = $archivo->insertar($parametros);
                    $msj = 'Archivo subido';
                } else {
                    $id = $datos['id'];
                    unset($datos['id']);
                    $insertado = $archivo->cambiar($parametros,$id);
                    $msj = 'Tabla actualizada';
                }

                if ($insertado == false) {
                    $error = true;
                    $msj = 'Error';
                }
            } else {
                $error = true;
                $msj = 'Error';
            }
        }
    } else {
        $error = true;
        $msj = 'Los datos estan vacios';
    }
} catch (\Throwable $th) {
    $error = true;
    $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
