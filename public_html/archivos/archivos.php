<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once('libs/Tarea.php');
require_once('libs/Limpiar_data.php');
require_once('libs/Grupo.php');
require_once('libs/Archivo.php');
error_reporting(0);
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$usuario->es_valido();
$tarea = new Tarea($pdoconnect);
$id_usuario = $usuario->get_id();
$archivo = new Archivo($id_usuario, $pdoconnect);
$grupo = new Grupo($id_usuario, $pdoconnect);
$grupo->set_group_by('group by id_chat_grupo');
$tarea->set_id_usuario($id_usuario);
$grupos = $grupo->get_grupo();

?>
<h1 class="title">Archivos</h1>

<?php if (count($grupos) > 0) { ?>
    <div class="container-fluid d-flex justify-content-end boton-flotante">
        <div class="row">
            <div class="col btn-mas">
                <a class="btn boton-mas" href="index.php?seccion=subir_archivo" role="button"> <i class="fa-solid fa-plus"></i></a>
            </div>
        </div>
    </div>
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
                                    $usuario_buscar = $value2['miembro'];
                                    $miembro = $grupo->get_dato_grupo($usuario_buscar);

                                ?>
                                    <li id="<?php echo $value['id_chat_grupo'] . '_' . $miembro[0]['id']; ?>" class="list-group-item">
                                        <?php echo '<p> Usuario: ' . ucfirst($miembro[0]['nombre']) . '</p>'; ?>
                                        <?php
                                        $archivo->set_id_grupo($value['id_chat_grupo']);
                                        $archivos = $archivo->get_archivos();
                                        if (count($archivos) > 0) {
                                        ?>
                                            <div>
                                                <?php
                                                foreach ($archivos as $key3 => $value3) {
                                                    if ($value3['usuario'] == $miembro[0]['id']) {
                                                        if ($value3['usuario'] == $id_usuario) {
                                                ?>
                                                            <a onclick="borrar('<?php echo $value['id_chat_grupo'] . '_' . $value3['id']; ?>')" class="btn-block mb-3 btn w-100 boton-borrar"> Borrar</a>
                                                    <?php  } ?>
                                                        <a onclick="descargar('<?php echo $value['id_chat_grupo'] . '_' . $value3['id']; ?>')" class="btn-block btn w-100 boton-descargar mb-3"> <i class="fa-solid fa-download mr-3"></i> Descargar</a>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                </div>

                <script>
                    var id_borrar = '';

                    function descargar(id) {
                        $.ajax({
                            type: "GET",
                            url: "archivos/descargar.php?id=" + id,
                            success: function(response) {
                                enviar_toast(response);
                                if (response.error == false) {
                                    downloadURI(response.ruta, response.archivo);

                                }
                            }
                        });
                    }

                    function downloadURI(uri, name) {
                        var link = document.createElement("a");
                        link.setAttribute('download', name);
                        link.href = uri;
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    }

                    function borrar_final() {
                        try {
                            $.ajax({
                                type: "GET",
                                url: "archivos/borrar.php?id=" + id_borrar,
                                success: function(response) {
                                    enviar_toast(response);
                                    if (response.error == false) {
                                        setTimeout(() => {
                                            window.location = 'index.php?seccion=archivos';
                                        }, 3000);
                                    }
                                }
                            });
                        } catch (error) {

                        }
                    }

                    function borrar(id) {
                        try {
                            id_borrar = id;
                            setTimeout(() => {
                                $('#modal').show();
                            }, 1200);
                        } catch (error) {

                        }

                    }


                    var myModalEl = document.getElementById('modal')
                    myModalEl.addEventListener('click', function(event) {
                        if (event.target.id == 'confirmar') {
                            borrar_final();
                        }
                    })
                </script>
            <?php
            }
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