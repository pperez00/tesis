<?php
error_reporting(0);
session_start();
require_once '../libs/pdoconnect.php';
require_once '../libs/Limpiar_data.php';
require_once '../libs/Usuario.php';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$datos = $_POST;
$enviar = array();
$error = false;
$where = '';
$msj = '';
try {
    if ($limpiar_data->validar_data($datos, -1) == true) {
        if ($limpiar_data->validar_email($datos['email']) == true) {
            $where = "id='" . $usuario->get_id() . "'";
            $parametros = array();
            $parametros['campos'] = 'count(*) as cantidad';
            $parametros['tabla'] = 'usuarios';
            $parametros['where'] = $where;
            $buscar_usuario = $pdoconnect->buscar_datos($parametros);
            if ($buscar_usuario[0]['cantidad'] == 1) {
                if ($limpiar_data->validar_pass($datos['pass']) == true) {
                    $datos['pass'] = password_hash($datos['pass'], PASSWORD_DEFAULT);
                    $parametros = array();
                    $datos['permiso'] = 2;
                    $parametros['tabla'] = 'usuarios';
                  
                    $usuario_carpeta_principal = '../usuarios/' . $datos['usuario'];
                    $carpetas = $usuario->get_carpetas();
                    $target_dir = $usuario_carpeta_principal . '/' . $carpetas[0]  . '/';
                    $nombre = basename($_FILES["foto"]["name"]);
                    $target_file = $target_dir . $nombre;
                    $imageFileType = end((explode(".", $nombre)));


                    $check = getimagesize($_FILES["foto"]["tmp_name"]);
                    if ($check == false) {
                        $error = true;
                        $msj = 'El archivo no es una imagen';
                    }

                    if (file_exists($target_file)) {
                        $error = true;
                        $msj = 'El archivo ya existe';
                    }

                    if ($_FILES["foto"]["size"] > 500000) {
                        $error = true;
                        $msj = 'El archivo es muy grande';
                    }

                    if (
                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif"
                    ) {
                        $error = true;
                        $msj = 'El archivo tiene una extension invalida';
                    }


                    if ($error == false) {
                        mkdir($usuario_carpeta_principal);
                        foreach ($carpetas as $key => $value) {
                            mkdir($usuario_carpeta_principal . '/' . $value);
                        }
                        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                            $datos['foto'] = $nombre;
                            $parametros['values'] = $datos;
                            $parametros['where'] = $where;
                            $resultado = $pdoconnect->cambiar($parametros);
                            if ($resultado == false) {
                                $error = true;
                                $msj = 'Error';
                            } else {
                                $msj = 'Datos guardados';
                            }
                        } else {
                            $error = true;
                            $msj = 'Error';
                        }
                    }
                } else {
                    $error = true;
                    $msj = 'Tu contraseña no es segura';
                }
            } else {
                $error = true;
                $msj = 'Error';
            }
        } else {
            $error = true;
            $msj = 'El email es invalido';
        }
    } else {
        $error = true;
        $msj = 'Tiene datos vacios';
    }
} catch (\Throwable $th) {
    $error = true;
    $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
