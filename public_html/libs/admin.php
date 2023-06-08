<?php
require_once('Usuario.php');
class Admin extends Usuario
{

    function __construct($pdoconnect)
    {
        parent::__construct($pdoconnect);
    }

    public function get_tablas()
    {
        $tablas = [];
        if ($this->get_permiso() == 1) {
            $tablas[0] = 'usuarios';
            $tablas[1] = 'tareas';
            $tablas[2] = 'permisos';
            $tablas[3] = 'grupos';
            $tablas[4] = 'estado_tareas';
            $tablas[5] = 'archivos_grupo';
            $tablas[6] = 'comprados';
        }
        return $tablas;
    }

    public function get_formularios()
    {
        $formularios = [];
        if ($this->get_permiso() == 1) {
            $formularios[0] = 'usu_form';            
            $formularios[1] = 'tar_form';
            $formularios[2] = 'permiso_form';
            $formularios[3] = 'grupo_form';
            $formularios[4] = 'estado_form';
            $formularios[5] = 'archivo_form';
            $formularios[6] = 'comprado_form';
        }
        return $formularios;
    }

    public function get_datos($id_tabla)
    {
        $parametros['tabla'] = $this->get_tablas()[$id_tabla];
        $parametros['where'] = ' 1 ';
        $parametros['campos'] = '*';
        $datos = $this->get_pdoconnect()->buscar_datos($parametros);
        return $datos;
    }

    public function get_datos_id($id_tabla,$id)
    {
        $parametros['tabla'] = $this->get_tablas()[$id_tabla];
        $parametros['where'] = "id='" . intval($id) . "'";
        $parametros['campos'] = '*';
        $datos = $this->get_pdoconnect()->buscar_datos($parametros);
        return $datos;
    }

    public function get_tabla_name($nombre)
    {
        $name = str_replace('_', ' ', $nombre);
        $name = ucfirst($name);
        return $name;
    }

    public function get_tabla($id)
    {
        $estado = false;
        if ($this->get_permiso() == 1) {
            if (isset($this->get_tablas()[intval($id)]) == true) {
                $estado = true;
            }
        }
        return $estado;
    }

    public function get_tabla_session_id()
    {
        return isset($_SESSION["id"]) ? $_SESSION["id"] : '';
    }

    public function borrar($id)
    {
        $borrado = false;
        if (intval($id) > 0 && $this->get_permiso() == 1) {
            $parametros['tabla'] = $this->get_tablas()[$this->get_tabla_session_id()];
            $parametros['where'] = "id='" . $id . "'";
            $borrado = $this->get_pdoconnect()->borrar($parametros);
        }
        return $borrado;
    }
}
