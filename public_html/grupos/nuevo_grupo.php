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
  $grupo->set_group_by('GROUP by nombre');
  if ($usuario->get_premium() == 0 && count($grupo->get_grupo()) >= 3) {
    $enviar_form = false;
  }
}
?>
<div class="container d-flex justify-content-center">
  <div class="col-lg-6 col-md-8 col-sm-12">
    <form class="gral-form" id="nuevo_grupo_form" action="grupos/crear_grupo.php">
      <label id="nuevo_grupo" class="text-dark" for="chk" aria-hidden="true">Nuevo grupo</label>
    
      <div class="input-group mt-2">
        <span class="input-group-text">Nombre</span>
        <input type="text" required class="form-control" name="nombre" id="nombre" placeholder="nombre del grupo">
      </div>
    
      <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
        <button <?php if ($enviar_form == false) : ?> disabled <?php endif; ?> type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
        <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>    
    </form>
  </div>
</div>

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
        if (data.error == false) {
          setTimeout(() => {
            window.location = 'index.php?seccion=grupos';
          }, 1000);
        }
      }
    });
  });
</script>