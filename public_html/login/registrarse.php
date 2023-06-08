<?php
error_reporting(0);
session_start();
require_once '../libs/pdoconnect.php';
require_once '../libs/Limpiar_data.php';
require_once '../libs/Usuario.php';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$datos = $_POST;
$enviar = array();
$error = false;
$msj = '';
try {
    if ($limpiar_data->validar_data($datos, -1) == true) {
        $permiso_actual = $usuario->get_permiso();
        if ($limpiar_data->validar_email($datos['email']) == true) {
            $parametros = array();
            
            if(isset($datos['id']) == false){
                $parametros['campos'] = 'count(*) as cantidad';
                $parametros['tabla'] = 'usuarios';
                $parametros['where'] = "usuario='" . $datos['usuario'] . "'";
                $buscar_usuario = $pdoconnect->buscar_datos($parametros);
            } else {
                $buscar_usuario[0]['cantidad'] = 0;
            }

            if ($buscar_usuario[0]['cantidad'] == 0) {
                if ($limpiar_data->validar_pass($datos['pass']) == true) {
                    $datos['pass'] = password_hash($datos['pass'], PASSWORD_DEFAULT);
                    $parametros = array();

                    if(isset($datos['permiso']) == false && $permiso_actual != 1){
                        $datos['permiso'] = 2;
                    }
    
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

                    if (file_exists($target_file) && isset($datos['id']) == false) {
                        $error = true;
                        $msj = 'El archivo ya existe';
                    }

                    if ($_FILES["foto"]["size"] > 10000000) {
                        $error = true;
                        $msj = 'El archivo es muy grande';
                    }

                    if (
                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif"
                    ) {
                        $error = true;
                        $msj = 'El archivo es una extension invalida';
                    }


                    if ($error == false) {
                        mkdir($usuario_carpeta_principal);
                        foreach ($carpetas as $key => $value) {
                            mkdir($usuario_carpeta_principal . '/' . $value);
                        }
                        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                            $datos['foto'] = $nombre;
                            $datos['premium'] = 0;
                            if(isset($datos['id']) == false){
                                $parametros['values'] = $datos;
                                $resultado = $pdoconnect->insertar($parametros);
                                $msj = 'Usuario registrado';
                            } else if($permiso_actual == 1){ 
                                $id = $datos['id'];
                                unset($datos['id']);
                                $parametros['values'] = $datos;
                                $parametros['where'] = "id='" . $id . "'";
                                $resultado = $pdoconnect->cambiar($parametros);
                                $msj = 'Usuario actualizado';
                            }
                          
                            if ($resultado == false) {
                                $error = true;
                                $msj = 'Error';
                                unset($target_file);
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
        $msj = 'Los daatos estan vacios';
    }
} catch (\Throwable $th) {
    $error = true;
    $msj = 'Error';
}

$enviar['msj'] = $msj;
$enviar['error'] = $error;

header('Content-Type: application/json');
echo json_encode($enviar, JSON_FORCE_OBJECT);
