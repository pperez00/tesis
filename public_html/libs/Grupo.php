<?php
require_once 'Conector.php';
require_once 'get_nombre_interfaz.php';
class Grupo extends Conector implements get_nombre_interfaz
{

   private $group_by;

   function __construct($id_usuario, Pdoconnect $pdoconnect)
   {
      parent::__construct($id_usuario, $pdoconnect);
   }

   public function get_parametros()
   {
      $parametros['tabla'] = 'grupos';
      $parametros['campos'] = 'miembro,nombre,id_chat_grupo,usuario';
      return $parametros;
   }

   public function insertar($datos = array(), $id_chat_grupo = '')
   {
      $insertado = false;
      if ($this->get_id_usuario() > 0) {
         $valores = $datos;
         $parametros['tabla'] = 'grupos';
         $id_chat_grupo = uniqid();
         $valores['id_chat_grupo'] = $id_chat_grupo;
         $parametros['values'] = $valores;
         $insertado = $this->get_pdoconnect()->insertar($parametros);
      }
      return $insertado;
   }

   public function cambiar($datos, $id_grupo)
   {
      $insertado = false;
      if ($this->get_permiso() == 1) {
         $valores = $datos;
         $parametros['tabla'] = 'grupos';
         $parametros['values'] = $valores;
         $parametros["where"] = "id='" . $id_grupo . "'";
         $insertado = $this->get_pdoconnect()->cambiar($parametros);
      }
      return $insertado;
   }

   public function set_group_by($group_by)
   {
      $this->group_by = $group_by;
   }

   public function get_grupo($where = '')
   {
      $parametros = $this->get_parametros();
      $parametros['where'] = "usuario='" . $this->get_id_usuario() . "'  " . $where . " or miembro='" . $this->get_id_usuario() . "' " . $where . ' ' . $this->group_by;
      $grupos = $this->get_pdoconnect()->buscar_datos($parametros);
      return $grupos;
   }

   public function get_grupo_sin_usuario($where = '')
   {
      $parametros = $this->get_parametros();
      $parametros['where'] = $where . ' ' . $this->group_by;
      $grupos = $this->get_pdoconnect()->buscar_datos($parametros);
      return $grupos;
   }

   public function get_dato_grupo($id_usuario = 0)
   {
      $datos = [];

      if (intval($id_usuario) > 0) {
         $parametros['tabla'] = 'usuarios';
         $parametros['campos'] = 'id,nombre,email,foto,permiso';
         $parametros['where'] = "id='" . $id_usuario . "'";
         $datos = $this->get_pdoconnect()->buscar_datos($parametros);
      }
      return $datos;
   }

   public function borrar_miembro($id_usuario = 0, $id_chat_grupo = '')
   {
      $borrado = false;

      try {
         if (intval($id_usuario) > 0) {
            $parametros['tabla'] = 'grupos';
            $parametros['where'] = "miembro='" . $id_usuario . "' and id_chat_grupo='" . $id_chat_grupo . "'";
            $borrado = $this->get_pdoconnect()->borrar($parametros);
         }
      } catch (\Throwable $th) {
      }
      return $borrado;
   }

   public function agregar_miembro($datos = array())
   {
      $insertado = false;
      if ($this->get_id_usuario() > 0) {
         $valores = $datos;
         $valores['usuario'] = $this->get_id_usuario();
         $parametros['tabla'] = 'grupos';
         $parametros['values'] = $valores;
         $insertado = $this->get_pdoconnect()->insertar($parametros);
      }
      return $insertado;
   }

   public function get_nombre($id = '')
   {
      $parametros['tabla'] = 'grupos grupo inner join usuarios on usuarios.id = grupo.usuario';
      $parametros['campos'] = 'grupo.nombre';
      $parametros['where'] = "grupo.id_chat_grupo ='" .  $id . "' and grupo.nombre != ''";
      $buscar = $this->get_pdoconnect()->buscar_datos($parametros);
      return $buscar;
   }

   public function get_usuario_grupo($id_chat_grupo = '', $usuario = 0, $miembro = 0)
   {
      $parametros = $this->get_parametros();
      $parametros['where'] = "id_chat_grupo='" . $id_chat_grupo . "' and usuario='" . $usuario . "' or id_chat_grupo='" . $id_chat_grupo . "' and miembro='" . $miembro . "' limit 1";
      $usuario_array = $this->get_pdoconnect()->buscar_datos($parametros);
      return $usuario_array;
   }

   public function get_miembros_grupo($id_chat_grupo)
   {
      $miembros = [];
      $parametros = $this->get_parametros();
      $parametros['where'] = "id_chat_grupo='" . $id_chat_grupo . "'";
      $miembros = $this->get_pdoconnect()->buscar_datos($parametros);
      if (count($miembros) > 0) {
         $usuario_array = $this->get_usuario_grupo($id_chat_grupo);
         $miembro['nombre'] = $usuario_array[0]['nombre'];
         $miembro['usuario'] = $usuario_array[0]['usuario'];
         $miembro['miembro'] = $usuario_array[0]['usuario'];
         $miembro['id_chat_grupo'] = $usuario_array[0]['id_chat_grupo'];
         array_push($miembro);
      }
      return $miembros;
   }

   public function get_grupos()
   {
      $datos = array();
      if ($this->get_id_usuario() > 0) {
         $parametros['tabla'] = 'grupos';
         $parametros['campos'] = 'id,nombre';
         $parametros['where'] = "usuario='" . $this->get_id_usuario() . "'";
         $datos = $this->get_pdoconnect()->buscar_datos($parametros);
      }
      return $datos;
   }
}
