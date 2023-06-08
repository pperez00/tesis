<?php
class Limpiar_data
{

    public function validar_email($email = '')
    {
        try {
            $validado = true;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validado = false;
            }
        } catch (\Throwable $th) {
            $validado = false;
        }
        return $validado;
    }

    public function limpiar($texto = '')
    {
        $dato = '';
        try {
            if (filter_var($texto, FILTER_SANITIZE_STRING) == true) {
                $dato = htmlspecialchars($texto);
            }
        } catch (\Throwable $th) {
        }
        return $dato;
    }

    public function validar_data($array = array(), $mayor = 0)
    {
        $estado = true;
        try {
            if (count($array) > 0) {
                foreach ($array as $key => $value) {

                    if ($key > $mayor) {
                        if (strlen($value) <= 0) {
                            $estado = false;
                        }
                    } else {
                        $estado = false;
                    }
                }
            } else {
                $estado = false;
            }
        } catch (\Throwable $th) {
            $estado = false;
        }
        return $estado;
    }

    public function validar_pass($pass = '')
    {
        $estado = true;
        try {
            $mayus = preg_match('@[A-Z]@', $pass);
            $minus = preg_match('@[a-z]@', $pass);
            $numeros    = preg_match('@[0-9]@', $pass);
            $especiales = preg_match('@[^\w]@', $pass);

            if (!$mayus || !$minus || !$numeros || $especiales || strlen($pass) < 8) {
                $estado = false;
            }
        } catch (\Throwable $th) {
            $estado = false;
        }
        return $estado;
    }
}
