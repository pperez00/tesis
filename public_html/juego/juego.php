<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
$pdoconnect = new Pdoconnect(new Limpiar_data);
session_start();
error_reporting(0);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
?>
<h1 class="title">Juego</h1>


<iframe class="w-100 h-75 mb-2 mt-2" src="https://kahoot.it/"></iframe>
