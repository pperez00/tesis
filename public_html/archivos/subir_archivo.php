<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Tarea.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Grupo.php');
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$tarea = new Tarea($pdoconnect);
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$grupo->set_group_by('group by id_chat_grupo');

$tarea->set_id_usuario($id_usuario);
$grupos = $grupo->get_grupo();

?>
<h1>Archivos</h1>
<?php if (count($grupos) > 0) { ?>
    <form class="subir mb-5 pb-1" id="subir">
        <label id="titulo_formulario" class="text-dark" for="chk" aria-hidden="true">Subir archivo</label>
        <div class="input-group mt-2 w-auto mb-3 ml-5">
            <span class="input-group-text">Grupo</span>
            <select class="form-select pr-5" id="grupo" name="grupo" required>
                <?php
                foreach ($grupos as $key => $value) {
                ?>
                    <option value="<?php echo $value['id_chat_grupo']; ?>"><?php echo ucfirst($value['nombre']); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="input-group mt-2">
            <span class="input-group-text">Archivo</span>
            <input required accept="application/pdf" type="file" required class="form-control" name="archivo" id="archivo">
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
            <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
            <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
        </div>
    </form>
    <script>
        $("#subir").submit(function(e) {

            e.preventDefault();
            var form = $(this);
            var archivo = $('#archivo')[0].files;
            var form_jq = $('#subir');

            if (archivo.length > 0) {
                $.ajax({
                    type: "POST",
                    url: 'archivos/subir.php',
                    data: new FormData(form_jq[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        enviar_toast(data);
                        if (data.error == false) {
                            form_jq.trigger('reset');
                            setTimeout(() => {
                                window.location = 'index.php?seccion=archivos';
                            }, 3000);
                        }
                    }
                });
            }
        });
    </script>
<?php } else {
?>
    <div id="vacio" class="alert alert-info mt-3" role="alert">
        <?php if ($permiso == 2) {
        ?>
            <p class="text-center">No tenes ningun grupo creado</p>
        <?php
        } else {
        ?>
            <p class="text-center">No hay ningun grupo creado</p>
        <?php
        }
        ?>
    </div>
<?php
}
?>