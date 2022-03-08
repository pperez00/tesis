<?php
class Grupo
{
    // Declaración de una propiedad
    private $imagen_grupo = '../img/mat.png';
    private $nombre_grupo = '';

    // Declaración de un método
    public function getNombre() 
    {
        echo $this->nombre_grupo;
    }
}
?>