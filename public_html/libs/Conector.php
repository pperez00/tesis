<?php
abstract class Conector
{
    private $id_usuario;
    private $pdoconnect;
    private $permiso = 0;

    function __construct($id_usuario, $pdoconnect)
    {
        $this->set_id_usuario($id_usuario);
        $this->pdoconnect = $pdoconnect;
    }

    public function set_permiso($permiso)
    {
        if (intval($permiso) > 0) {
            $this->permiso = $permiso;
        }
    }

    public function get_permiso()
    {
        return $this->permiso;
    }

    public function get_pdoconnect()
    {
        return $this->pdoconnect;
    }

    public function get_id_usuario()
    {
        return $this->id_usuario;
    }

    public function set_id_usuario($id_usuario)
    {
        if (intval($id_usuario) > 0) {
            $this->id_usuario = $id_usuario;
        }
    }

    public abstract function get_parametros();

    public abstract function cambiar($datos,$id);
    
    public abstract function insertar($datos = array());
}
