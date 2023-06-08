<?php
require_once 'Grupo.php';
class Chat extends Grupo
{
    function __construct($id_usuario, Pdoconnect $pdoconnect)
    {
        parent::__construct($id_usuario, $pdoconnect);
    }

    public function buscar_chats()
    {
        $claves = array();
        $datos = array();
        $array = array();
        foreach ($this->get_grupo() as $key => $value) {
            $datos_miembro = $this->get_dato_grupo($value['miembro']);
            $datos[$value['id_chat_grupo'] . '_' . $key] = $datos_miembro;
            array_push($claves, ['clave' => $value['id_chat_grupo'], 'nombre' => $value['nombre']]);
        }
        $array['datos'] = $datos;
        $array['claves'] = $claves;
        return $array;
    }

    public function get_id_chat_grupo_session()
    {
        return isset($_SESSION["id_chat_grupo"]) ? $_SESSION["id_chat_grupo"] : '';
    }

    public function get_datos_validar_session()
    {
        return isset($_SESSION["datos_validar"]) ? $_SESSION["datos_validar"] : '';
    }

    public function get_cantidad_miembro_chat_grupo($id_miembro = '')
    {
        $parametros['tabla'] = 'grupos';
        $parametros['campos'] = 'count(*) as cantidad';
        $parametros['where'] = "id_chat_grupo='" . $this->get_id_chat_grupo_session() . "' and miembro='" . $id_miembro . "'";
        $buscar_count = $this->get_pdoconnect()->buscar_datos($parametros);
        return $buscar_count;
    }

    public function get_usuario_grupo_cantidad($dato  = '')
    {
        
        $valido = array();
        if (strpos($dato, '_') !== false) {
            $array = explode('_', $dato);
            $id_grupo = $array[0];
            $id_usuario = $array[1];
            $parametros['tabla'] = 'grupos';
            $parametros['campos'] = 'count(*) as cantidad';
            $parametros['where'] = "id_chat_grupo='" . $id_grupo . "' and miembro='" . $id_usuario . "' or id_chat_grupo='" . $id_grupo . "' and usuario='" . $id_usuario . "'";
            $buscar_count = $this->get_pdoconnect()->buscar_datos($parametros);

            if(intval($buscar_count[0]['cantidad']) > 0){
                $valido['estado'] = true;
                $valido['id_usuario'] = $id_usuario;
                $valido['id_grupo'] = $id_grupo;
                if(count($array) == 2){
                    $valido['id_tarea'] = $array[2];
                }
            } else {
                $valido['estado'] = false;
            }
          
        } else {
            $valido['estado'] = false;
        }
        return $valido;
    }
}
