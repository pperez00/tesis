<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Grupo.php');
session_start();
$pdoconnect = new Pdoconnect(new Limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
unset($_SESSION['id_chat_grupo']);
error_reporting(0);
$enviar_form = true;
$grupo = new Grupo($id_usuario, $pdoconnect);
if ($permiso != 1) {
  if ($usuario->get_premium() == 0 && count($grupo->get_grupos()) >= 3) {
    $enviar_form = false;
  }
}
?>
<h1>Nuevo grupo</h1>
<form class="nuevo_grupo_form gral-form pb-3" id="nuevo_grupo_form" action="grupos/crear_grupo.php">
  <label id="nuevo_grupo" class="text-dark" for="chk" aria-hidden="true">Crear grupo</label>

  <div class="input-group mt-2">
    <span class="input-group-text">Nombre</span>
    <input type="text" required class="form-control" name="nombre" id="nombre">
  </div>
  <?php require_once 'grupos/lista_personas.php'; ?>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
    <button <?php if ($enviar_form == false) : ?> disabled <?php endif; ?> type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
    <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>

</form>

<script>
  $("#nuevo_grupo_form").submit(function(e) {

    e.preventDefault();
    var form = $(this);
    var form_jq = $('#nuevo_grupo_form');
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
        console.log(data);
        if (data.error == false) {
          setTimeout(() => {
            window.location = 'index.php?seccion=grupos';
          }, 3000);
        }

      }
    });
  });
</script>