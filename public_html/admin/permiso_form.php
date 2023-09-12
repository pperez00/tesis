<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/admin.php');
require_once('libs/Limpiar_data.php');
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Admin($pdoconnect);
$permiso = $usuario->get_permiso();
$id_tabla = $usuario->get_tabla_session_id();
$estado = $usuario->get_tabla($id_tabla);
if ($permiso == 1 && $estado == true) {
?>
    <form action="admin/cargar_nombre.php" class="permiso gral-form m-2 p-2" id="permiso">
        <label id="titulo_formulario" class="" for="chk" aria-hidden="true">Añadir</label>
        <div class="input-group mt-2">
            <span class="input-group-text">Nombre</span>
            <input type="text" required class="form-control" name="nombre" id="nombre">
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
            <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
            <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
        </div>
    </form>
    <script>
        $("#permiso").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var form_jq = $('#permiso');
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
                            window.location = 'index.php?seccion=admin&id=2';
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