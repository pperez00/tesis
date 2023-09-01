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
         $parametros['campos'] = 'id,nombre,email,foto,permiso,premium';
         $parametros['where'] = "id='" . $id_usuario . "'";
         $datos = $this->get_pdoconnect()->buscar_datos($parametros);
      }
      return $datos;
   }

   private function buscar_cant_grupo($id = 0, $where = 'G.usuario = G.miembro and u.id')
   {

      $buscar = array();
      $buscar['campos'] = 'COUNT(G.usuario) as cantidad, U.id, U.usuario, U.premium';
      $buscar['tabla'] = 'usuarios U JOIN grupos G ON U.id = G.usuario';
      $buscar['where'] = $where . ' = ' . $id . ' GROUP BY G.usuario';

      $user = $this->get_pdoconnect()->buscar_datos($buscar);
      if ($user == null) {
         $datos = $this->get_dato_grupo($id);
         $user[0]['cantidad'] = 0;
         $user[0]['id'] = $id;
         $user[0]['usuario'] = $datos[0]['nombre'];
         $user[0]['sin_grupo'] = true;
         $user[0]['premium'] = $datos[0]['premium'];
      }
      return $user;
   }

   private function permite_borrar($id = 0, $id_chat_grupo = '', $id_usuario = 0)
   {
      $update['tabla'] = 'grupos';
      $update['values'] = ["usuario" => $id];
      $update['where'] = " id_chat_grupo='" . $id_chat_grupo . "'";
      $parametros['tabla'] = 'grupos';
      $parametros['where'] =  "miembro='" . $id_usuario . "' and id_chat_grupo='" . $id_chat_grupo . "'";
      $borrado['borrado'] = $this->get_pdoconnect()->borrar($parametros);
      $this->get_pdoconnect()->cambiar($update);
      return $borrado;
   }

   public function borrar_miembro($id_usuario = 0, $id_chat_grupo = '')
   {
      $borrado = array();
      $permitir_borrar = 0;
      $borrado['borrado'] = false;
      try {
         if (intval($id_usuario) > 0) {
            $parametros['tabla'] = 'grupos';
            $miembro = 'miembro';
            $parametros['where'] = $miembro . "='" . $id_usuario . "' and id_chat_grupo='" . $id_chat_grupo . "'";

            if ($this->get_id_usuario() == $id_usuario) {

               $buscar['tabla'] = 'grupos';
               $buscar['campos'] = 'miembro';
               $buscar['where'] = $miembro . "!='" . $id_usuario . "' and id_chat_grupo='" . $id_chat_grupo . "' limit 1";
               $id = $this->get_pdoconnect()->buscar_datos($buscar)[0]['miembro'];
               if ($id != $id_usuario && $id != null) {

                  $buscar['campos'] = 'count(*) as cantidad';
                  $buscar['where'] =  "usuario ='" . $id . "'";
                  $buscar_premium['tabla'] = 'usuarios';
                  $buscar_premium['campos'] = 'premium';
                  $buscar_premium['where'] =  "id ='" . $id . "'";
                  $premium = $this->get_pdoconnect()->buscar_datos($buscar_premium);
                  $cantidad = intval($this->get_pdoconnect()->buscar_datos($buscar)[0]['cantidad']);
                  if ($cantidad < 3 or intval($premium[0]['premium']) == 1) {
                     $permitir_borrar = 1;
                  }
               } else {
                  $borrado['owner'] = false;
                  $permitir_borrar = 2;
                  $borrado['msj'] = 'Se borro el usuario';
                   $borrado['borrado'] = $this->get_pdoconnect()->borrar($parametros);
               }

               if ($permitir_borrar == 1) {
                   $borrado = $this->permite_borrar($id, $id_chat_grupo, $id_usuario);
                  $borrado['owner'] = true;
               } else if($permitir_borrar == 0){
                  $borrado['msj'] = "Todos los demas miembros alcanzaron la maxima cantidad de grupos";
                  $borrado['borrado'] = false;
                  $borrado['owner'] = false;
                  $grupos = $this->get_miembros_grupo($id_chat_grupo);
                  $borrado_usuario = false;
                  foreach ($grupos as $key => $value) {
                     if ($key > 1) {
                        $buscar_cantidad = $this->buscar_cant_grupo($value['miembro'])[0];

                        if (intval($buscar_cantidad['cantidad']) < 3 && $borrado_usuario == false or intval($buscar_cantidad['premium']) == 1 && $borrado_usuario == false) {
                          $borrado = $this->permite_borrar($value['miembro'], $id_chat_grupo, $id_usuario);
                           $borrado['msj'] = 'Se borro el usuario';
                           $borrado['borrado'] = true;
                           $borrado_usuario = true;
                        }
                     }
                  }
               }
            } else {
               $borrado['admin'] = false;
               $borrado['borrado'] = $this->get_pdoconnect()->borrar($parametros);
               $borrado['msj'] = 'Se borro el usuario';
            }
         }
      } catch (\Throwable $th) {
         $borrado['borrado'] = false;
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

   public function get_miembros_grupo($id_chat_grupo = '')
   {
      $miembros = array();
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
         $parametros['where'] = "usuario='" . $this->get_id_usuario() . "'" . $this->group_by;
         $datos = $this->get_pdoconnect()->buscar_datos($parametros);
      }
      return $datos;
   }
}
