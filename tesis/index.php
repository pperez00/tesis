<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Grupy</title>

    <?php $seccion = isset($_GET["seccion"]) ? $_GET["seccion"] : 'grupos'; ?>

</head>
<body>
    <?php require('navbar.php'); ?>
    <main class="container-fluid">
    
    <?php
       switch($seccion)
       {               
            case "login":
               include "login/login.php";
               break;               
            case "archivos":
               include "archivos/archivos.php";
               break;           
            case "chat":
               include "chat/chat.php";
               break;
            case "tareas":
               include "tareas/tareas.php";
               break;
            case "juego":
               include "juego/juego.php";
               break;
            case "grupos":
               default:
               include "grupos/grupos.php";               
       }       
       
       ?>
       </main>

<footer class="container-fluid">
   <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. At totam hic porro doloribus perferendis
       atque ab praesentium nemo, architecto eius recusandae reprehenderit cupiditate. Adipisci debitis sit 
       velit dolor enim itaque?
   </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>



