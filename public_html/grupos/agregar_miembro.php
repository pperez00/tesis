<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Grupo.php');
require_once('libs/Limpiar_data.php');
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
ob_start();
error_reporting(0);
session_start();
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$id_chat_grupo = $limpiar_data->limpiar(isset($_GET["chat_grupo"]) ? $_GET["chat_grupo"] : '');
if (strlen($id_chat_grupo) == 0) {
    header('Location: index.php');
} else {
    if (count($grupo->get_usuario_grupo($id_chat_grupo, $id_usuario)) == 0) {
        header('Location: index.php');
    }
    $grupos = $grupo->get_grupo("and id_chat_grupo='" . $id_chat_grupo . "'");
    if ($grupos != null) {
        $_SESSION['id_chat_grupo'] = $id_chat_grupo;
?>
        <div class="container d-flex justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <form class="pb-3 gral-form" id="agregar_miembro_form" action="grupos/agregar_miembro_grupo.php">
                    <label id="nuevo_grupo" class="text-dark" for="chk" aria-hidden="true">Invitar</label>
                    <div class="input-group mt-2">
                        <span class="input-group-text">Miembro</span>
                        <input type="text" required class="form-control" name="miembro" id="miembro" placeholder="nombre de usuario">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
                        <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
                    </div>
                    <script>
                        $("#agregar_miembro_form").submit(function(e) {
                            e.preventDefault();
                            var form = $(this);
                            var form_jq = $('#agregar_miembro_form');
                            var fornm_data = new FormData(form_jq[0]);
                            var cargando = $('#cargando');
                            cargando.addClass('d-none');
                            $.ajax({
                                type: "POST",
                                url: form.attr('action'),
                                data: fornm_data,
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function(data) {
                                    enviar_toast(data);
                                    if (data.error == false) {
                                        setTimeout(() => {
                                            window.location = 'index.php?seccion=grupos';
                                        }, 1000);
                                    }

                                }
                            });
                        });
                    </script>
                </form>
            </div>
        </div>
<?php
    } else {
        header('Location: index.php');
    }
} ?>