<?php
class Usuario
{
    private $pdoconnect;
    private $permiso = '';

    function __construct(Pdoconnect $pdoconnect)
    {
        $this->pdoconnect =  $pdoconnect;
    }

    public function get_id()
    {
        $id = 0;
        try {
            if (isset($_SESSION['usuario']['id']) == true) {
                $id = intval($_SESSION['usuario']['id']);
            }
        } catch (\Throwable $th) {
        }
        return $id;
    }

    public function get_nombre_usuario()
    {
        $nombre = '';
        $campo = 'usuario';
        try {
            $buscar = $this->buscar($campo);
            if ($buscar != null) {
                $nombre = $buscar[0][$campo];
            }
        } catch (\Throwable $th) {
        }
        return $nombre;
    }

    private function buscar($campo = '')
    {
        $buscar = array();
        try {
            $id = $this->get_id();
            if ($id > 0) {
                $parametros['tabla'] = 'usuarios';
                $parametros['campos'] = $campo;
                $parametros['where'] = "id='" . $id . "'";
                $buscar = $this->pdoconnect->buscar_datos($parametros);
            }
        } catch (\Throwable $th) {
        }
        return $buscar;
    }

    public function get_nombre()
    {
        $nombre = '';
        $campo = 'nombre';
        try {
            $buscar = $this->buscar($campo);
            if ($buscar != null) {
                $nombre = $buscar[0][$campo];
            }
        } catch (\Throwable $th) {
        }
        return $nombre;
    }

    public function get_informacion_id($id, $campo)
    {
        $dato = '';
        try {
            if ($id > 0) {
                $parametros['tabla'] = 'usuarios';
                $parametros['campos'] = $campo;
                $parametros['where'] = "id='" . $id . "'";
                $buscar = $this->pdoconnect->buscar_datos($parametros);
                if ($buscar != null) {
                    $dato = $buscar[0][$campo];
                }
            }
        } catch (\Throwable $th) {
        }
        return $dato;
    }

    public function get_email()
    {
        $email = '';
        $campo = 'email';
        try {
            $buscar = $this->buscar($campo);
            if ($buscar != null) {
                $email = $buscar[0][$campo];
            }
        } catch (\Throwable $th) {
        }
        return $email;
    }

    public function get_premium()
    {
        $premium = 0;
        $campo = 'premium';
        try {
            $buscar = $this->buscar($campo);
            if ($buscar != null) {
                $premium = $buscar[0][$campo];
            }
        } catch (\Throwable $th) {
        }
        return $premium;
    }

    public function get_permiso()
    {
        $campo = 'permiso';
        try {
            $buscar = $this->buscar($campo);
            if ($buscar != null) {
                $this->permiso = $buscar[0][$campo];
            }
        } catch (\Throwable $th) {
        }
        return $this->permiso;
    }

    public function es_valido()
    {
        if (intval($this->permiso) <= 0) {
            ob_start();
            header('Location: index.php');
        }
    }

    public function get_id_name($nombre = '')
    {
        try {
            $id = 0;
            $parametros = array();
            $parametros['where'] = "usuario='" . $nombre . "'";
            $parametros['tabla'] = 'usuarios';
            $parametros['campos'] = 'id';
            $data = $this->pdoconnect->buscar_datos($parametros);
            if (count($data) > 0) {
                $id = $data[0]['id'];
            }
        } catch (\Throwable $th) {
        }
        return $id;
    }

    public function get_foto($usuario = '')
    {
        $foto = '';
        try {
            $parametros = array();
            $parametros['tabla'] = 'usuarios';
            $parametros['campos'] = 'foto';
            $parametros['where'] = "usuario='" . $usuario . "'";
            $foto_sql = $this->pdoconnect->buscar_datos($parametros)[0]['foto'];
            if ($foto_sql != null) {
                $foto_ruta = 'usuarios/' . $usuario . '/' . $this->get_carpetas()[0] . '/' . $foto_sql;
                if (is_file($foto_ruta)) {
                    $foto = $foto_ruta;
                }
            }
        } catch (\Throwable $th) {
        }
        return $foto;
    }

    public function get_carpetas()
    {
        $carpetas = [];
        $carpetas[0] = 'fotos';
        $carpetas[1] = 'Pdf';
        return $carpetas;
    }

    public function get_pdoconnect()
    {
        return $this->pdoconnect;
    }
}
