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
$id_tabla = $_GET['id'];
$estado = $usuario->get_tabla($id_tabla);
if ($permiso == 1 && $estado == true) {
    $_SESSION['id'] = $id_tabla;
    $parametros['tabla'] = 'usuarios';
    $parametros['where'] = 'permiso!=1';
    $parametros['campos'] = 'count(*) as cantidad';

    $parametros_grupos['tabla'] = 'grupos';
    $parametros_grupos['where'] = ' 1 GROUP by id_chat_grupo';
    $parametros_grupos['campos'] = 'id_chat_grupo';

    $parametros_archivos['tabla'] = 'archivos_grupo';
    $parametros_archivos['where'] = ' 1 ';
    $parametros_archivos['campos'] = 'count(*) as cantidad';

    $parametros_tareas['tabla'] = 'tareas';
    $parametros_tareas['where'] = 'estado!=3';
    $parametros_tareas['campos'] = 'count(*) as cantidad';
?>
    <h1 class="title">Panel de administración</h1>

    <div class="container mb-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter primary">
                    <span class="count-numbers text-center"> <?php echo $pdoconnect->buscar_datos($parametros_tareas)[0]['cantidad']; ?> </span>
                    <span class="count-name text-center">Tareas</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-counter danger">
                    <span class="count-numbers text-center"> <?php echo $pdoconnect->buscar_datos($parametros_archivos)[0]['cantidad']; ?> </span>
                    <span class="count-name text-center">Archivos</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-counter success">
                    <span class="count-numbers text-center"><?php echo count($pdoconnect->buscar_datos($parametros_grupos)); ?> </span>
                    <span class="count-name text-center">Grupos</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-counter info">
                    <span class="count-numbers text-center"><?php echo $pdoconnect->buscar_datos($parametros)[0]['cantidad']; ?></span>
                    <span class="count-name text-center">Usuarios</span>
                </div>
            </div>
        </div>
    </div>

    <div class="tabla" id="tabla"></div>

    <div class="d-none" id="form_div">
        <?php
        include_once($usuario->get_formularios()[$id_tabla] . '.php');
        ?>
    </div>
    <script>
        var indice = 0;

        function bajar(top) {
            window.scroll({
                top: top,
                behavior: 'smooth'
            });
        }
        $.ajax({
            type: "GET",
            url: "admin/buscar.php",
            success: function(response) {
                if (response.error == false) {
                    var data = [];
                    var columns = [];
                    $.each(response.columnas, function(indexInArray, valueOfElement) {
                        $.each(valueOfElement, function(index, columna) {
                            columns.push({
                                index: columna,
                                title: columna.replace('_', ' '),
                                type: 'string',
                                width: 200,
                            });
                        });
                    });
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        data.push(valueOfElement);
                    });
                    var grid = new FancyGrid({
                        id: 'tabla',
                        title: {
                            text: response.titulo,
                            style: {
                                'text-align': 'center'
                            }
                        },
                        renderTo: 'tabla',
                        theme: 'bootstrap',
                        selModel: 'row',
                        paging: true,
                        i18n: 'es',
                        sortable: true,
                        clicksToEdit: true,
                        contextmenu: [
                            'copy',
                            'copy+',
                            '-',
                            {
                                text: '<i class="fa-solid fa-trash-can"></i> Borrar',
                                imageCls: 'custom-menu-item-cls',
                                sideText: '<i class="fa-solid fa-trash-can"></i> Borrar',
                                handler: function(event) {
                                    setTimeout(() => {
                                        $('#modal').show();
                                    }, 200);
                                }
                            },
                            {
                                text: '<i class="fa-solid fa-plus"></i> Agregar',
                                imageCls: 'custom-menu-item-cls',
                                sideText: '<i class="fa-solid fa-plus"></i> Agregar',
                                handler: function(event) {
                                    indice = 0;
                                    $('#form_div').removeClass('d-none');
                                    $('#form_div > form').trigger('reset');
                                    $('#id').remove();
                                    bajar(1200);
                                    $('#titulo_formulario').text('Agregar');
                                }
                            }, {
                                text: '<i class="fa-solid fa-pen-to-square"></i> Editar',
                                imageCls: 'custom-menu-item-cls',
                                sideText: '<i class="fa-solid fa-pen-to-square"></i> Editar',
                                handler: function(event) {
                                    $.ajax({
                                        type: "GET",
                                        url: "admin/buscar_informacion.php?id=" + indice,
                                        success: function(response) {
                                            if (response.error == false) {
                                                $('#form_div').removeClass('d-none');
                                                $('#id').remove();
                                                bajar(1200);
                                                $('#form_div > form').append('<input type="hidden" name="id" id="id" value="' + indice + '">');
                                                $.each(response.datos[0], function(indexInArray, valueOfElement) {
                                                    var input = $('#' + indexInArray);
                                                    if (input.attr('type') != 'file') {
                                                        input.val(valueOfElement);
                                                        if (input.attr('type') == 'checkbox') {
                                                            input.prop('checked', Boolean(Number.parseInt(valueOfElement)));
                                                        }
                                                    }
                                                });
                                                $('#titulo_formulario').text('Editar');
                                            } else {
                                                enviar_toast(response);
                                            }

                                        }
                                    });

                                }
                            }

                        ],
                        tbar: [{
                            type: 'search',
                            width: 1165,
                            emptyText: 'Buscar',
                            paramsMenu: true,
                            paramsText: 'Parametros'
                        }],
                        data: data,
                        columns: columns,
                        events: [{
                            cellclick: function(grid, o) {
                                indice = o.id;
                            }
                        }]
                    });

                } else {
                    enviar_toast(response);
                }
            }
        });

        function borrar_final() {
            $.ajax({
                type: "GET",
                url: "admin/borrar.php?id=" + indice,
                success: function(response) {
                    enviar_toast(response);
                    if (response.error == false) {
                        window.location = 'index.php?seccion=admin&id=<?php echo $id_tabla; ?>';
                    }
                }
            });
        }

        var myModalEl = document.getElementById('modal')
        myModalEl.addEventListener('click', function(event) {
            if (event.target.id == 'confirmar') {
                borrar_final();
            }
        })
    </script>
<?php
} else {
    ob_start();
    header('Location: index.php');
}
