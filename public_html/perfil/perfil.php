<?php
session_start();
error_reporting(0);
require_once 'libs/pdoconnect.php';
require_once 'libs/Usuario.php';
require_once 'libs/Limpiar_data.php';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
unset($_SESSION['id']);
$usuario->es_valido();
$nombre_usuario = $usuario->get_informacion_id($usuario->get_id(), 'usuario');
$foto = $usuario->get_foto($nombre_usuario);
?>
<h1 class="title">Perfil</h1>
<div class="row row-cols-1 row-cols-md-2 g-4">

    <div class="col">
        <div class="card perfil">
            <div class="card-header">
                <h5 class="card-title text-center"><?php echo ucfirst($usuario->get_nombre()); ?></h5>
            </div>
            <div class="card-body">

                <div class="foto-perfil">
                    <img src="<?php echo $foto; ?>" class="card-img-top rounded-start mr-5" alt="foto">
                </div>
                <div class="datos-perfil">
                    <p class="card-text ml-5">E-mail: <?php echo $usuario->get_email(); ?></p>
                    <p class="card-text ml-5">Usuario: <?php echo $nombre_usuario; ?></p>
                </div>

            </div>

        </div>
    </div>

    <div class="col">

        <div class="card perfil">
            <?php require_once 'login/form_sign.php'; ?>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        $('#titulo_formulario').html('Cambiar');
        var usuario = $('#usuario');
        usuario.val('<?php echo $usuario->get_nombre_usuario(); ?>');
        usuario.addClass('disabled');  
        $('#email').val('<?php echo $usuario->get_email(); ?>');
        $('#nombre').val('<?php echo $usuario->get_nombre(); ?>');
    });

    $("#signup").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var foto = $('#foto')[0].files;

        if (foto.length > 0) {
            $.ajax({
                type: "POST",
                url: 'perfil/cambiar.php',
                data: new FormData($('#signup')[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    enviar_toast(data);
                    setTimeout(() => {
                        window.location = 'index.php?seccion=perfil';
                    }, 1000);
                }
            });
        }
    });
</script>