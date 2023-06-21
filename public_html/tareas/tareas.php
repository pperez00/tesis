<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Grupo.php');
require_once('libs/Tarea.php');
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$grupo->set_group_by('group by id_chat_grupo');
$tarea = new Tarea($pdoconnect);
$grupos = $grupo->get_grupo();
unset($_SESSION['datos_validar']);
?>

<h1 class="title">Tareas</h1>

<?php
if (count($grupos) > 0) {
?>
  <div class="row tarjeta-grupo">
    <?php foreach ($grupos as $key => $value) { ?>
      <div class="col tarjeta">
        <div class="card grupos mb-3">
          <div class="imagen-grupo">
            <img loading="lazy" src="./img/mat.png" class="card-img-top img-grupo" alt="imagen del grupo">
          </div>
          <div class="card-header sticky-top nombre-grupo">
            <h3 class="card-title text-center"><?php echo ucfirst($value['nombre']); ?></h3>
            <h5 class="text-center">Dueño: <?php echo ucfirst($usuario->get_informacion_id($value['usuario'], 'nombre')); ?></h5>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <?php
              foreach ($grupo->get_miembros_grupo($value['id_chat_grupo']) as $key2 => $value2) {
                $miembro = $grupo->get_dato_grupo($value2['miembro'])
              ?>

                <div id="<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id']; ?>" class="card m-3">

                  <div class="usuario-card card-header">
                    <h4 class="text-center mb-3"> <?php echo ucfirst($miembro[0]['nombre']); ?></h4>
                    <div class="d-flex">
                      <a onclick="dar_tarea('<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id']; ?>')" class="btn boton mx-auto"><i class="fa-solid fa-plus"></i>
                        Nueva Tarea
                      </a>

                    </div>
                  </div>
                  <?php
                  $tarea_array = $tarea->get_tareas_id_usuario_grupo($miembro[0]['id'], $value['id_chat_grupo']);

                  if (intval($miembro[0]['permiso']) == 2) :
                  ?>
                    <ul class="tarea list-group">
                      <li class="list-group list-group-flush">
                        <?php
                        for ($key_tarea = 0; $key_tarea < count($tarea_array); $key_tarea++) {
                        ?>

                          <?php
                          if (count($tarea_array) > 0) : ?>

                            <div class="tarea-desc-est list-group-item">
                              <p>
                                <strong> Descripción:</strong> <?php echo ucfirst($tarea_array[$key_tarea]['nombre']); ?>
                              </p>
                              <p>
                                <strong>Estado:</strong> <?php echo ucfirst($tarea_array[$key_tarea]['estado']); ?>
                              </p>
                            </div>
                            <div class="botones-tarea card-footer">
                              <a onclick="borrar_tarea('<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id'] . '_' . $tarea_array[$key_tarea]['id']; ?>')" class="btn boton-borrar col-auto m-2"><i class="fa-solid fa-trash-can"></i></a>
                              <a onclick="cambiar_estado('<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id'] . '_' . $tarea_array[$key_tarea]['id']; ?>')" class="btn boton-editar col-auto m-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            </div>
                      </li>
                  <?php
                          endif;
                        }
                  ?>
                    </ul>
                  <?php
                  endif;
                  ?>
                </div>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <script>
    var id_tarea = '';

    function dar_tarea(id) {
      window.location = 'index.php?seccion=agregar_tarea&id=' + id;
    }

    function borrar_tarea(id) {
      id_tarea = id;
      setTimeout(() => {
        $('#modal').show();
      }, 1200);
    }

    function borrar_tarea_final() {
      $.ajax({
        type: "GET",
        url: "tareas/borrar_tarea.php?id=" + id_tarea,
        success: function(response) {
          if (response.error == false) {
            $('#' + id_tarea).remove();
            window.location = 'index.php?seccion=tareas';
          }
          enviar_toast(response);
        }
      });
    }

    function cambiar_estado(id) {
      window.location = 'index.php?seccion=cambiar_estado&id=' + id;
    }

    var myModalEl = document.getElementById('modal')
    myModalEl.addEventListener('click', function(event) {
      if (event.target.id == 'confirmar') {
        borrar_tarea_final();
      }
    })
  </script>
<?php

} else {
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