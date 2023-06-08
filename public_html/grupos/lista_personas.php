<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Chat.php');
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
session_start();
error_reporting(0);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id = $usuario->get_id();
$parametros = array();
$chat = new Chat($id, $pdoconnect);
$id_chat_grupo = $chat->get_id_chat_grupo_session();
$parametros['where'] = "id !='" . $id . "' and permiso=2";
$parametros['tabla'] = 'usuarios';
$parametros['campos'] = 'nombre,id';
$usuarios = $pdoconnect->buscar_datos($parametros);
if(intval($id) > 0):
?>
<div class="input-group mt-2 w-auto">
    <span class="input-group-text">Agregar miembro</span>
    <select required class="form-select pr-5" id="miembro" name="miembro">
    <option value="" selected disabled hidden> Elegui uno </option>
        <?php
        foreach ($usuarios as $key => $value) {
            $crear = true;
            if (strlen($id_chat_grupo) > 0) {
                if (intval($chat->get_cantidad_miembro_chat_grupo($value['id'])[0]['cantidad']) > 0) {
                    $crear = false;
                }
            }
            if ($crear == true) {
        ?>
                <option value="<?php echo $value['id']; ?>"><?php echo ucfirst($value['nombre']); ?></option>
        <?php
            }
        }
        if($permiso == 1){
            ?>
             <option value="1">Admin</option>
            <?php
        }
        ?>
    </select>
</div>
<?php endif;?>