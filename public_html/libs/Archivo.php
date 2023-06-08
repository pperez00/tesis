<?php
require_once 'Conector.php';
class Archivo extends Conector
{

    private $id_chat_grupo = '';

    function __construct($id_usuario, Pdoconnect $pdoconnect)
    {
        parent::__construct($id_usuario, $pdoconnect);
    }

    public function set_id_grupo($id_chat_grupo)
    {
        $this->id_chat_grupo = $id_chat_grupo;
    }

    public function get_archivo($id = '')
    {
        $parametros = $this->get_parametros();
        $parametros['where'] = "id='" . $id . "' and usuario='" . $this->get_id_usuario() . "' and grupo='" . $this->id_chat_grupo . "'";
        $datos = $this->get_pdoconnect()->buscar_datos($parametros);
        return $datos;
    }

    public function get_archivo_grupo($id = '')
    {
        $parametros = $this->get_parametros();
        $parametros['where'] = "id='" . $id . "' and grupo='" . $this->id_chat_grupo . "'";
        $datos = $this->get_pdoconnect()->buscar_datos($parametros);
        return $datos;
    }

    public function get_archivos()
    {
        $parametros = $this->get_parametros();
        $parametros['where'] = "grupo='" . $this->id_chat_grupo . "'";
        $datos = $this->get_pdoconnect()->buscar_datos($parametros);
        return $datos;
    }

    public function get_parametros()
    {
        $parametros = array();
        $parametros['tabla'] = 'archivos_grupo';
        $parametros['campos'] = "archivo,usuario,id";
        return $parametros;
    }

    public function insertar($datos = array())
    {
        $parametros = array();
        $parametros['tabla'] = 'archivos_grupo';
        $valores['archivo'] = $datos['nombre'];
        $valores['grupo'] = $datos['grupo'];
        if (isset($datos['usuario']) == false) {
            $valores['usuario'] = $this->get_id_usuario();
        }  
        $parametros['values'] = $valores;
        $insertado =  $this->get_pdoconnect()->insertar($parametros);
        return $insertado;
    }

    public function cambiar($datos = array(), $id)
    {
        $insertado = false;
        if (intval($this->get_permiso()) == 1 && intval($id) > 0) {
            $parametros = array();
            $parametros['tabla'] = 'archivos_grupo';
            $valores['archivo'] = $datos['nombre'];
            $valores['grupo'] = $datos['grupo'];
            if (isset($datos['usuario']) == false) {
                $valores['usuario'] = $this->get_id_usuario();
            }
            $parametros['values'] = $valores;
            $parametros['where'] = "id='" - $id . "'";
            $insertado =  $this->get_pdoconnect()->cambiar($parametros);
        }
        return $insertado;
    }

    public function borrar($id)
    {
        $borrado = false;
        if (intval($id) > 0 && intval($this->get_permiso()) > 0) {
            $parametros['tabla'] = 'archivos_grupo';
            $parametros['where'] = "id='" . $id . "' and grupo='" . $this->id_chat_grupo . "'";
            if ($this->get_permiso() == 2) {
                $parametros['where'] = $parametros['where'] . " and usuario='" . $this->get_id_usuario() . "'";
            }
            $borrado = $this->get_pdoconnect()->borrar($parametros);
        }
        return $borrado;
    }
}
