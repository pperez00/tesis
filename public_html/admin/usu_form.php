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
   include_once('login/form_sign.php');
?>
   <script>
      $("#signup").submit(function(e) {
         e.preventDefault();
         var form = $(this);
         var foto = $('#foto')[0].files;
         var form_jq = $('#signup');

         if (foto.length > 0) {
            $.ajax({
               type: "POST",
               url: 'login/registrarse.php',
               data: new FormData(form_jq[0]),
               cache: false,
               contentType: false,
               processData: false,
               success: function(data) {
                  enviar_toast(data);
                  if (data.error == false) {
                     form_jq.trigger('reset');
                     setTimeout(() => {
                        window.location = 'index.php?seccion=admin&id=0';
                     }, 3000);
                  }
               }
            });
         }
      });
   </script>
<?php
} else {
   ob_start();
   header('Location: index.php');
}
?>