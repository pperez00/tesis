<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Chat.php');
require_once('libs/Tarea.php');
session_start();
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$chat = new Chat($id_usuario, $pdoconnect);
$chat->set_group_by('group by id_chat_grupo');
$tarea = new Tarea($pdoconnect);
$datos = $_GET;
if ($limpiar_data->validar_data($datos, -1)) {
    $dato_validar = $chat->get_usuario_grupo_cantidad($datos['id']);

    $_SESSION['datos_validar'] = $dato_validar;
?>

    <form class="cambiar_estado gral-form" id="cambiar_estado" action="tareas/cambiar_estado.php">
        <h2 class="text-dark mb-4 text-center" for="chk" aria-hidden="true">Cambiar estado</h2>
        <?php
        require_once('tareas/lista_estados.php');
        ?>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
            <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
            <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
        </div>
    </form>
    <script>
        $("#cambiar_estado").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var form_jq = $('#cambiar_estado');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: new FormData(form_jq[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    enviar_toast(data);
                    if (data.error == false) {
                        setTimeout(() => {
                            window.location = 'index.php?seccion=tareas';
                        }, 1000);
                    }

                }
            });


        });
    </script>
<?php

} else {
    ob_start();
    header('Location: index.php');
}
?>