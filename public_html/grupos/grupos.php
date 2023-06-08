<?php
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Grupo.php');
require_once('libs/Limpiar_data.php');
$pdoconnect = new Pdoconnect(new Limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
unset($_SESSION['id_chat_grupo']);
$id_usuario = $usuario->get_id();
$grupo = new Grupo($id_usuario, $pdoconnect);
$grupo->set_group_by('group by id_chat_grupo');
$grupos = $grupo->get_grupo();

?>
<h1 class="title">Grupos</h1>

<div class="container-fluid d-flex justify-content-end boton-flotante">
    <div class="row">
        <div class="col btn-mas">
            <a class="btn" href="index.php?seccion=nuevo" role="button"> <i class="fa-solid fa-plus icono"></i></a>
        </div>

    </div>
</div>
<?php if (count($grupos) > 0) { ?>
    <div class="container d-flex justify-content-center align-items-center">
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
                            <h5 class="text-center">Miembros del grupo</h5>
                            <ul class="list-group list-group-flush scroll_y lista_usuarios">
                                <?php
                                foreach ($grupo->get_miembros_grupo($value['id_chat_grupo']) as $key2 => $value2) {
                                    $miembro = $grupo->get_dato_grupo($value2['miembro'])
                                ?>
                                    <li id="<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id']; ?>" class="list-group-item">
                                        <?php echo '<p> Usuario: ' . ucfirst($miembro[0]['nombre']) . '</p>'; ?>
                                        <?php if ($value2['miembro'] == $id_usuario  or $value2['usuario'] == $id_usuario or $permiso == 1) { ?>
                                            <a onclick="borrar('<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id']; ?>')" class="btn-block btn w-100 boton-borrar">Borrar</a>
                                        <?php  } ?>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>

                        <?php if (count($grupo->get_usuario_grupo($value['id_chat_grupo'], $id_usuario)) > 0 or $permiso == 1) { ?>
                            <a href="index.php?seccion=agregar_miembro&chat_grupo=<?php echo $value['id_chat_grupo']; ?>" class="btn boton m-4">Agregar</a>
                        <?php } else {
                            echo '<div class="pb-3 pt-3"></div>';
                        } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } else {
?>
    <div id="vacio" class="alert alert-info mt-3" role="alert">
        <p class="text-center">No tenes ningun grupo</p>
    </div>
<?php
} ?>

<script>
    var id_borrar = '';

    function borrar_final() {
        $.ajax({
            type: "GET",
            url: "grupos/borrar_usuario.php?id=" + id_borrar,
            success: function(response) {
                cerrar_modal();
                if (response.error == false) {
                    $('#' + id_borrar).remove();
                    setTimeout(() => {
                        if (response.cantidad <= 0) {
                            window.location = 'index.php?seccion=grupos';
                        }
                    }, 1800);
                }
                enviar_toast(response);
            }
        });
    }

    function borrar(id) {
        id_borrar = id;
        setTimeout(() => {
            $('#modal').show();
        }, 1200);

    }


    var myModalEl = document.getElementById('modal')
    myModalEl.addEventListener('click', function(event) {
        if (event.target.id == 'confirmar') {
            borrar_final();
        }
    })
</script>