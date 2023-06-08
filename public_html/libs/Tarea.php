<?php
require_once 'Conector.php';
require_once 'get_nombre_interfaz.php';
class Tarea extends Conector implements get_nombre_interfaz
{

    private $id_grupo;

    public static $sin_tarea_key = 9999999;

    function __construct(Pdoconnect $pdoconnect)
    {
        $this->get_datos_validar_session();
        parent::__construct($this->get_id_usuario(), $pdoconnect);
    }


    public function set_id_grupo($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }

    public function get_id_grupo(){
        return $this->id_grupo;
    }

    private function get_datos_validar_session()
    {
        $datos_validar = isset($_SESSION["datos_validar"]) ? $_SESSION["datos_validar"] : [];
        if (count($datos_validar) > 0) {
            $this->set_id_grupo($datos_validar['id_grupo']);
            $this->set_id_usuario($datos_validar['id_usuario']);
        }
        return $datos_validar;
    }

    public function insertar($datos = array())
    {
        $insertado = false;
        if ($this->get_permiso() == 2 or $this->get_permiso() == 1) {
            $valores = $datos;
            $parametros['tabla'] = 'tareas';
            $parametros['values'] = $valores;
            $insertado = $this->get_pdoconnect()->insertar($parametros);
        }
        return $insertado;
    }

    public function cambiar($datos,$id){
        $insertado = false;
        if ($this->get_permiso() == 1) {
            $valores = $datos;
            $parametros['tabla'] = 'tareas';
            $parametros['where'] = "id='" . $id . "'";
            $parametros['values'] = $valores;
            $insertado = $this->get_pdoconnect()->cambiar($parametros);
        }
        return $insertado;
    }

    public function cambiar_estado_tarea($id_tarea, $estado){
        $cambiado = false;
        if(intval($id_tarea) > 0 && intval($estado) > 0){
            $parametros['tabla'] = 'tareas';
            $valores['estado'] = $estado;
            $parametros['values'] = $valores;
            $parametros['where'] = "id='" . $id_tarea . "' and id_grupo='" . $this->id_grupo . "'";
            if($this->get_permiso() == 2){
                $parametros['where'] = $parametros['where'] . " and usuario='" . $this->get_id_usuario() . "'";
            }

            $cambiado = $this->get_pdoconnect()->cambiar($parametros);
        } 
        return $cambiado;
    }

    public function get_nombre($id = '')
    {
        $parametros['tabla'] = 'tareas';
        $parametros['campos'] = 'nombre';
        $parametros['where'] = "id='" . $id . "'";
        $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar;
    }    

    public function get_parametros(){
        $parametros['tabla'] = 'tareas tarea inner join estado_tareas estados on estados.id = tarea.estado';
        $parametros['campos'] = 'tarea.nombre,tarea.id,estados.nombre as estado,estados.id as id_estado';
        return $parametros;
    }

    
    public function get_tareas_id_usuario_grupo($id_usuario, $id_grupo)
    {
        $parametros = $this->get_parametros();
        $parametros['where'] = "usuario='" . intval($id_usuario) . "' and id_grupo='" . $id_grupo . "' and estado !=3";
        $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar;
    }

    public function get_tareas($where){
        $parametros = $this->get_parametros();
        $parametros['where'] = "usuario='" . intval($this->get_id_usuario()) . "' " . $where;
        $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar;
    }

    public function borrar_tarea($id = 0)
    {
        $borrar = false;
        if ($this->get_permiso() == 2 or $this->get_permiso() == 1) {
            $parametros['tabla'] = 'tareas';
            $parametros['where'] = "id='" . intval($id) . "'";
            $borrar = $this->get_pdoconnect()->borrar($parametros);
        }
        return $borrar;
    }

    public function get_estados(){
        $parametros['tabla'] = 'estado_tareas';
        $parametros['campos'] = '*';
        $parametros['where'] = " 1 ";
        $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar;
    }

    public function get_permiso_estado($id = 0){
        $parametros['tabla'] = 'tareas';
        $parametros['campos'] = 'estado';
        $parametros['where'] = "id='" . intval($id) . "'";
        print_r($parametros);
        $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar;
    }

}
