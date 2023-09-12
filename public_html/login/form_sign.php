<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/admin.php');
require_once('libs/Limpiar_data.php');
?>
<form class="signup p-3" id="signup">
    <label id="titulo_formulario" class="text-white" for="chk" aria-hidden="true">Registrarse</label>
    <div class="input-group mt-2">
        <span class="input-group-text">Usuario</span>
        <input type="text" required class="form-control" name="usuario" id="usuario">
    </div>
    <div class="input-group mt-2">
        <span class="input-group-text">Nombre y apellido</span>
        <input type="text" required class="form-control" name="nombre" id="nombre">
    </div>
    <div class="input-group mt-2">
        <span class="input-group-text">E-mail</span>
        <input type="email" required class="form-control" name="email" id="email">
    </div>
    <div class="input-group mt-2">
        <span class="input-group-text">Contraseña</span>
        <input type="password" autocomplete="Escribí la contraseña" required class="form-control" name="pass" id="pass">
    </div>
    <?php
    $limpiar_data = new Limpiar_data;
    $pdoconnect = new Pdoconnect($limpiar_data);
    $usuario = new Admin($pdoconnect);
    $permiso = $usuario->get_permiso();
    if ($permiso == 1 && $usuario->get_tabla_session_id() != '') {
    ?>
        <div class="input-group mt-2 w-auto">
            <span class="input-group-text">Permiso</span>
            <select required class="form-select " id="permiso" name="permiso">
                <option value="" selected disabled hidden> Elegí uno </option>
                <?php
                $parametros['where'] = ' 1 ';
                $parametros['campos'] = '*';
                $parametros['tabla'] = 'permisos';
                foreach ($pdoconnect->buscar_datos($parametros) as $key => $value) {
                ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo ucfirst($value['nombre']); ?></option>
                <?php

                }
                ?>
            </select>
        </div>
    <?php
    }
    ?>
    <!-- input file -->
    <div class="input-group mt-2">
        <span class="input-group-text">Foto de perfil</span>
        <input accept="image/png, image/gif, image/jpeg" type="file" required class="form-control" name="foto" id="foto">
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 px-2">
        <button type="submit" class="btn boton"> <i class="fa-solid fa-paper-plane-top"></i>> Enviar </button>
        <button type="reset" class="btn boton-borrar"><i class="fa-solid fa-trash"></i> Borrar </button>
    </div>

</form>