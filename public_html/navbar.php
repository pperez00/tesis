<?php
session_start();
require_once 'libs/pdoconnect.php';
require_once 'libs/admin.php';
require_once 'libs/Limpiar_data.php';
$limpiar_data = new Limpiar_data;
$pdoconnect = new Pdoconnect($limpiar_data);
$admin = new Admin($pdoconnect);
$id_user = $admin->get_id();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" onclick="nav_boton('inicio')" href="index.php?seccion=inicio">
            <img loading="lazy" class="img-fluid logo" src="img/logo-no-background.svg" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav py-2 px-3">
                <li class="nav-item">
                    <a id="inicio" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=inicio"><i class="fa-sharp fa-solid fa-house"></i> Inicio</a>
                </li>
                <!-- <li class="nav-item">
                    <a id="quienes_somos" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=quienes_somos">Quienes somos</a>
                </li> -->
                <li class="nav-item">
                    <a id="plan" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=plan"><i class="fa-sharp fa-solid fa-money-bill"></i> Planes</a>
                </li>
                <?php if ($id_user == 0) : ?>
                    <li class="nav-item">
                        <a id="login" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=login"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesion</a>
                    </li>
                <?php
                endif;
                if ($id_user > 0) :
                ?>
                    <li class="nav-item">
                        <a id="grupos" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=grupos"> <i class="fa-sharp fa-solid fa-people-group"></i> Grupos</a>
                    </li>
                    <li class="nav-item">
                        <a id="archivos" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=archivos"><i class="fa-solid fa-file"></i> Archivos</a>
                    </li>
                    <li class="nav-item">
                        <a id="tareas" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=tareas"><i class="fa-solid fa-list-check"></i> Tareas</a>
                    </li>
                    <li class="nav-item">
                        <a id="chat" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=chat"><i class="fa-solid fa-comments"></i> Chat</a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle" id="juego_dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gamepad"></i> Juego</a>
                        <ul class="dropdown-menu" aria-labelledby="juego_dropdown">
                            <a id="juego" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=juego"><i class="fa-solid fa-gamepad"></i> Jugar</a>
                            <a id="crear_juego" onclick="nav_boton(this.id)" class="nav-link" href="https://create.kahoot.it/auth/login?next=%2Fcreator" target='_blank'><i class="fa-solid fa-plus"></i>Nuevo juego</a>
                        </ul>
                    </li>
                    <div class="btn-group">
                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link dropdown-toggle" id="usuario_dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo ucfirst($admin->get_nombre()); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="usuario_dropdown">
                                <li class="nav-item">
                                    <a id="perfil" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=perfil"> <i class="fa-solid fa-user"></i> Perfil</a>
                                </li>
                                <?php if ($admin->get_permiso() == 1) : ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <?php
                                    foreach ($admin->get_tablas() as $key => $value) {
                                    ?>
                                        <li class="nav-item">
                                            <a id="<?php echo $key; ?>" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=admin&id=<?php echo $key; ?>"> <i class="fa-solid fa-table"></i> <?php echo $admin->get_tabla_name($value); ?></a>
                                        </li>
                                <?php
                                    }
                                endif;
                                ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="nav-item">
                                    <a id="cerrar" onclick="nav_boton(this.id)" class="nav-link" href="index.php?seccion=cerrar"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
                                </li>
                            </ul>
                        </li>
                    </div>
                <?php
                endif;
                ?>
            </ul>
        </div>
    </div>
</nav>