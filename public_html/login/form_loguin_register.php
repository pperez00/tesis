<?php
session_start();
require_once('libs/pdoconnect.php');
require_once('libs/admin.php');
require_once('libs/Limpiar_data.php');
?>



<div class="wrapper">
    <!-- formulario de loguin -->
    <div class="form-box login">
        <h2>Login</h2>
        <form class="login" action="login/verificar.php" id="login_form">
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                <input type="text" required name="usuario" id="usuario">
                <label>Usuario</label>
            </div>
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" required name="pass" id="pass">
                <label>Password</label>
            </div>
            <div class=" recordar-olvidar">
                <label><input type="checkbox">Recuérdame</label>
                <a href="#">¿Olvidaste la contraseña?</a>
            </div>
            <button type="submit" class="btn btn-loguin">Ingresar</button>
            <div class="login-register">
                <p>¿No tienes una cuenta?
                    <a href="#" class="register-link">Registrarse</a>
                </p>
            </div>
        </form>
    </div>
    <!-- formulario de registro -->
    <div class="form-box register">
        <h2>Registrarse</h2>
        <form id="signup">
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <input type="text" required name="usuario" id="usuario">
                <label>Usuario</label>
            </div>
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <input type="text" required name="nombre" id="nombre">
                <label>Nombre y apellido</label>
            </div>
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" required name="email" id="email">
                <label>Email</label>
            </div>
            <div class="input-box">
                <span class="icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" required name="pass" id="pass">
                <label>Password</label>
            </div>
            <div class="input-box">
                <input accept="image/png, image/gif, image/jpeg" type="file" required class="form-control" name="foto" id="foto">
                <label>Foto de perfil</label>
            </div>
            <div class=" recordar-olvidar">
                <label><input type="checkbox">Acepto los términos y condiciones</label>
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
                        <option value="" selected disabled hidden> Elegui uno </option>
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

            <button type="submit" class="btn btn-loguin">Registrarse</button>
            <div class="login-register">
                <p>¿Ya tienes una cuenta?
                    <a href="#" class="login-link">Ingresar</a>
                </p>
            </div>
        </form>
    </div>
</div>