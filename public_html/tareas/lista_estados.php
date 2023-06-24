<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Tarea.php');
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
session_start();
error_reporting(0);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id = $usuario->get_id();
$tarea = new Tarea($pdoconnect);
$parametros = array();
if (intval($id) > 0) :
?>
    <div class="input-group mt-2 w-auto">
        <span class="input-group-text">Estado</span>
        <select required class="form-select " id="estado" name="estado">
            <option value="" selected disabled hidden> Elegí uno </option>
            <?php

            foreach ($tarea->get_estados() as $key => $value) {
            ?>
                <option value="<?php echo $value['id']; ?>"><?php echo ucfirst($value['nombre']); ?></option>
            <?php

            }
            ?>
        </select>
    </div>
<?php endif; ?>