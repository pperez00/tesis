<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="js/jquery.js"></script>
   <link href="https://cdn.fancygrid.com/fancy.min.css" rel="stylesheet">
   <script src="https://cdn.fancygrid.com/fancy.min.js"></script>
   <script src="https://kit.fontawesome.com/40236df442.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="./css/style.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
   <link rel="shortcut icon" href="img/logo-no-background.svg" type="image/x-icon">
   <title>Grupy</title>
</head>

<body>
   <?php
   $seccion = isset($_GET["seccion"]) ? $_GET["seccion"] : 'inicio';

   ob_start();
   error_reporting(0);
   require('navbar.php');
   require_once 'libs/pdoconnect.php';
   require_once 'libs/Usuario.php';
   require_once  'libs/Limpiar_data.php';
   $limpiar_data = new Limpiar_data;
   $usuario = new Usuario(new Pdoconnect($limpiar_data));
   $id_usuario = $usuario->get_id();
   ?>

   <script>
      var toastList = [];
      $(document).ready(function() {
         $('.boton-borrar').html('<i class="fa-solid fa-trash-can"></i>');
         $('.borrar_alert').html('<i class="fa-solid fa-trash-can"></i> Si');
         $('.btn-success:not(button)').html('<i class="fa-solid fa-plus"></i>');
         try {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            toastList = toastElList.map(function(toastEl) {
               return new bootstrap.Toast(toastEl)
            })
            let id = getCookie('link');
            $('#' + id).addClass('active');
         } catch (error) {

         }
      });

      function getCookie(name) {
         const value = `; ${document.cookie}`;
         const parts = value.split(`; ${name}=`);
         if (parts.length === 2) return parts.pop().split(';').shift();
      }

      function enviar_toast(data) {
         try {
            var toast = $('#toast');
            toast.removeClass('bg-success');
            toast.removeClass('bg-danger');
            if (data.error == false) {
               toast.addClass('bg-success');
            } else {
               toast.addClass('bg-danger');
            }
            $('#tost_msj').html(data.msj);
            toastList[0].show();
         } catch (error) {

         }
      }

      function nav_boton(id) {
         document.cookie = 'link=' + id;
      }

      function cerrar_modal() {
         $('#modal').hide();
      }
   </script>

   <div class="toast-container">
      <div id="toast" class="toast align-items-center position_fixed" role="alert" aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div id="tost_msj" class="toast-body"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
   </div>

   <div id="modal" class="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">¿Estas seguro? </h5>
               <button onclick="cerrar_modal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer btn-group">
               <button id="cerrar" type="button" onclick="cerrar_modal()" class="btn btn-primary" data-bs-dismiss="modal"> <i class="fa-solid fa-xmark"></i> No</button>
               <button id="confirmar" type="button" class="btn boton-borrar borrar_alert"> Si</button>
            </div>
         </div>
      </div>
   </div>

   <main class="container-fluid d-flex justify-content-center align-items-centers">
      <div id="cargando" class="spinner-grow d-none position_absolute" role="status"></div>
      <?php
      switch ($seccion) {
         case "perfil":
            include_once "perfil/perfil.php";
            break;
         default:
         case "login":
            if ($id_usuario > 0) {
               include_once "grupos/grupos.php";
               unset($_SESSION['id']);
            } else {
               include_once "login/login.php";
               unset($_SESSION['id']);
            }
            break;
         case "archivos":
            include_once "archivos/archivos.php";
            unset($_SESSION['id']);
            break;
         case "chat":
            include_once "chat/chat.php";
            unset($_SESSION['id']);
            break;
         case "tareas":
            include_once "tareas/tareas.php";
            unset($_SESSION['id']);
            break;
         case "agregar_tarea":
            include_once "tareas/agregar_tarea.php";
            unset($_SESSION['id']);
            break;
         case "crear_juego":
            include_once "juego/crear_juego.php";
            unset($_SESSION['id']);
            break;
         case "juego":
            include_once "juego/juego.php";
            unset($_SESSION['id']);
            break;
         case "grupos":
            include_once "grupos/grupos.php";
            unset($_SESSION['id']);
            break;
         case 'nuevo':
            include_once "grupos/nuevo_grupo.php";
            unset($_SESSION['id']);
            break;
         case 'agregar_miembro':
            include_once "grupos/agregar_miembro.php";
            unset($_SESSION['id']);
            break;
         case 'inicio':
            include_once "inicio.php";
            break;
         case 'quienes_somos':
            include_once "quienes_somos.php";
            break;
         case 'cerrar':
            header('Location: index.php');
            session_destroy();
            break;
         case 'cambiar_estado':
            include_once "tareas/estado_cambiar.php";
            unset($_SESSION['id']);
            break;
         case 'subir_archivo':
            require_once('archivos/subir_archivo.php');
            unset($_SESSION['id']);
            break;
         case 'admin':
            require_once('admin/admin.php');
            break;
         case 'plan':
            require_once('plan/plan.php');
            break;
      }

      if (isset($_SESSION['error']) == true) {
      ?>
         <script>
            var data = [];
            data.error = Boolean('<?php echo $_SESSION['error']; ?>');
            data.msj = '<?php echo $_SESSION['mensaje_comprado']; ?>';
            setTimeout(() => {
               enviar_toast(data);
            }, 1500);
         </script>
      <?php
      }
      unset($_SESSION['error']);
      unset($_SESSION['mensaje_comprado']);
      ?>
   </main>

   <footer class="text-center bg-light d-inline p-2">
      <div class="d-inline">
         <p class="sin_salto">&copy; 2022 - Grupy </p>
         <a href="mailto:grupy@gmail.com">grupy@gmail.com</a>
      </div>

   </footer>

   <script src="js/bootstrap.js"></script>
   <script src="js/login.js"></script>
</body>

</html>