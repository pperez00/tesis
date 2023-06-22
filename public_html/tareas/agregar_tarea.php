<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Chat.php');
$limpiar_data = new Limpiar_data;
session_start();
error_reporting(0);
$datos = $_GET;
unset($_SESSION['datos_validar']);
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$chat = new Chat($id_usuario, $pdoconnect);
if ($limpiar_data->validar_data($datos, -1)) {
    $dato_validar = $chat->get_usuario_grupo_cantidad($datos['id']);
    if ($permiso > 0) {
        if ($dato_validar['estado'] == true) {
            $_SESSION['datos_validar'] = $dato_validar;
?>

        <h1 id="nuevo_grupo" class="title" for="chk" aria-hidden="true">Asignar tarea</h1>
            <form class="nueva_tarea_form gral-form pb-3" id="nueva_tarea_form" action="tareas/dar_tarea.php">

                <div class="input-group mt-2">
                    <span class="input-group-text">Descripción</span>
                    <textarea required class="form-control" name="nombre" id='nombre' id="" cols="30" rows="10"></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
                    <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
                    <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>


            </form>
            <script>
                $("#nueva_tarea_form").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var form_jq = $('#nueva_tarea_form');
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
    } else {
        ob_start();
        header('Location: index.php');
    }
} else {
    ob_start();
    header('Location: index.php');
}
?>