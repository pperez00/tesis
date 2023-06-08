<h1>Chat</h1>
<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/Usuario.php');
require_once  'libs/Limpiar_data.php';
$limpiar_data = new Limpiar_data;
$usuario = new Usuario(new Pdoconnect($limpiar_data));
$permiso = $usuario->get_permiso();
$usuario->es_valido();
?>

<script>
  (function(t, a, l, k, j, s) {
    s = a.createElement('script');
    s.async = 1;
    s.src = "https://cdn.talkjs.com/talk.js";
    a.head.appendChild(s);
    k = t.Promise;
    t.Talk = {
      v: 3,
      ready: {
        then: function(f) {
          if (k) return new k(function(r, e) {
            l.push([f, r, e])
          });
          l
            .push([f])
        },
        catch: function() {
          return k && new k()
        },
        c: l
      }
    };
  })(window, document, []);
</script>
<h2 class="text-center text-white">Grupos de chats</h2>

<div class="row">
  <ul class="nav col justify-content-center chat_ul scroll_y" id="chats"> </ul>
  <div class="chat_div mt-3" id="talkjs-container">
    <div id="vacio" class="alert alert-info d-none" role="alert">
      <p class="text-center">No tenes ningun chat</p>
    </div>
  </div>
</div>

<script>
  var inbox_inicial = [];
  var chatbox = [];
  var id_seleccionado = -1;

  function elegir_chat(id) {
    chatbox.select(conversaciones[id]);
    var boton = $('#' + id + ' a');
    if (id_seleccionado > -1) {
      var boton_2 = $('#' + id_seleccionado + ' a');
      boton_2.removeClass('btn-info');
      boton_2.addClass('btn-success');
    }
    boton.addClass('btn-info');
    boton.removeClass('btn-success');
    id_seleccionado = id;
  }

  var conversation = [];
  var conversaciones = [];
  var me = [];
  Talk.ready.then(function() {
    me = new Talk.User({
      id: '<?php echo $usuario->get_id(); ?>',
      name: '<?php echo ucfirst($usuario->get_nombre()); ?>',
      email: '<?php echo $usuario->get_email(); ?>',
      photoUrl: '<?php echo $usuario->get_foto($usuario->get_informacion_id($usuario->get_id(), 'usuario')); ?>',
      welcomeMessage: 'Hola',
    });
    window.talkSession = new Talk.Session({
      appId: 'tQm6RYde',
      me: me,
    });
    $.ajax({
      type: "get",
      url: "chat/buscar_chat.php",

      success: function(response) {
        if (response.error == false) {

          setTimeout(() => {
            $('#cargando').addClass('d-none');
            if (response.cantidad == 0) {
              $('#chats').removeClass('scroll_y');
              $('#vacio').removeClass('d-none');
            }
          }, 1200);

          var contador = 0;
          var id_chat_grupo_actual = '';

          if (response.cantidad > 0) {
            $.each(response.claves, function(indexInArray, valueOfElement) {
              if (id_chat_grupo_actual != valueOfElement) {
                conversation = talkSession.getOrCreateConversation(valueOfElement['clave']);
                conversaciones.push(conversation);
                id_chat_grupo_actual = valueOfElement['clave'];
                conversation.setParticipant(me);
                if (valueOfElement['nombre'].length > 0) {
                  var boton = '<li id="' + indexInArray + '" class="list-group-item chat_grupo bg-transparent"> <a class="btn btn-success w-100" onclick="elegir_chat(' + indexInArray + ')">' + valueOfElement['nombre'] + '</a></li>';
                  $('#chats').append(boton);
                }

              }
              var miembro = response.datos[valueOfElement['clave'] + '_' + contador];
              var miembro_user = new Talk.User({
                id: miembro[0].id,
                name: miembro[0].nombre,
                email: miembro[0].email,
                photoUrl: miembro[0].foto,
                welcomeMessage: 'Hola'
              });
              conversation.setParticipant(miembro_user);
              contador++;
            });
            chatbox = talkSession.createChatbox();
            $.each(conversaciones, function(indexInArray, valueOfElement) {
              if (valueOfElement != null) {
                elegir_chat(indexInArray);
              }

            });
            chatbox.mount(document.getElementById("talkjs-container"));

          }

        } else {
          enviar_toast(data);
        }
      }
    });

  });
  $('#cargando').removeClass('d-none');
</script>