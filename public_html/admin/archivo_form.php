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
?>
   <form class="subir mb-5 pb-1" id="subir">
      <label id="titulo_formulario" class="text-dark" for="chk" aria-hidden="true">Subir archivo</label>
      <?php
      include_once('grupos/lista_personas.php');
      require_once('tareas/lista_estados.php');
      ?>
      <div class="input-group mt-2 w-auto">
         <span class="input-group-text">Agregar grupo</span>
         <select required class="form-select " id="grupo" name="grupo">
            <option value="" selected disabled hidden> Elegí uno </option>
            <?php
            $parametros['tabla'] = 'grupos';
            $parametros['where'] = ' 1 GROUP by id_chat_grupo';
            $parametros['campos'] = 'id_chat_grupo,nombre';
            foreach ($pdoconnect->buscar_datos($parametros) as $key => $value) {
            ?>
               <option value="<?php echo $value['id_chat_grupo']; ?>"><?php echo ucfirst($value['nombre']); ?></option>
            <?php
            }
            ?>
         </select>
      </div>
      <div class="input-group mt-2">
         <span class="input-group-text">Archivo</span>
         <input required accept="application/pdf" type="file" required class="form-control" name="archivo" id="archivo">
      </div>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
        <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
        <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
    </div>
   </form>
   <script>
      $(document).ready(function () {

         var meimbro = $('#miembro');
         meimbro.attr("name", "usuario");
         meimbro.attr("id", "usuario");


      });
      $("#subir").submit(function(e) {
         e.preventDefault();
         var form = $(this);
         var foto = $('#archivo')[0].files;
         var form_jq = $('#subir');

         if (foto.length > 0) {
            $.ajax({
               type: "POST",
               url: 'admin/subir_archivo.php',
               data: new FormData(form_jq[0]),
               cache: false,
               contentType: false,
               processData: false,
               success: function(data) {
                  enviar_toast(data);
                  if (data.error == false) {
                     form_jq.trigger('reset');
                     setTimeout(() => {
                        window.location = 'index.php?seccion=admin&id=6';
                     }, 1000);
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