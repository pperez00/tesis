<?php
session_start();
require_once('../libs/pdoconnect.php');
require_once('../libs/Usuario.php');
require_once('../libs/Archivo.php');
require_once('../libs/Limpiar_data.php');
require_once('../libs/Grupo.php');

error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$archivo = new Archivo($id_usuario, $pdoconnect);
$dato = $_GET['id'];
$error = false;
$msj = '';
try {
    if (strpos($dato, '_') !== false && intval($id_usuario) > 0) {
        $separar = explode('_', $dato);
        $id_grupo = $separar[0];
        $id_archivo = $separar[1];
        $archivo->set_id_grupo($id_grupo);
        $archivo_buscar = $archivo->get_archivo($id_archivo);
        if (count($archivo_buscar) > 0 && count($grupo->get_grupo(" and id_chat_grupo='" . $id_grupo . "'")) > 0) {
            $usuario_carpeta = $usuario->get_informacion_id($archivo_buscar[0]['usuario'], 'usuario');
            $ruta =  $usuario_carpeta . "/" . $usuario->get_carpetas()[1] . "/" . $archivo_buscar[0]['archivo'];
            if (file_exists('../usuarios/' . $ruta)) {
                $archivo->set_permiso($permiso);
                $borrado = $archivo->borrar($id_archivo);
                if ($borrado == true && $error == false) {
                    $borrar_archivo = unlink('../usuarios/' . $ruta);
                    if ($borrar_archivo == true) {
                        $msj = 'Archivo borrado';
                    } else {
                        $error = true;
                        $msj = 'El archivo no existe';
                    }
                } else {
                    $error = true;
                    $msj = 'El archivo no existe';
                }
            } else {
                $error = true;
                $msj = 'El archivo no existe';
            }
        } else {
            $error = false;
            $msj = 'Archivo borrado';
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
