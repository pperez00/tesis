<h1>Plan</h1>
<?php
require_once('libs/pdoconnect.php');
require_once 'mercadopago_vendor/autoload.php';
require_once('libs/Limpiar_data.php');
require_once('libs/Usuario.php');
$limpiar_data = new Limpiar_data();
$pdoconnect = new Pdoconnect($limpiar_data);
$usuario = new Usuario($pdoconnect);
$permiso = $usuario->get_permiso();
$carrito = array();
if ($permiso > 0) {
    MercadoPago\SDK::setAccessToken('TEST-5281291799127776-020809-94afdf863d3a342366bfc8efdc67d2cf-1201752');
    $item = new MercadoPago\Item();
    $item->title = 'Modo premium';
    $item->quantity = 1;
    $item->id = 'Premium';
    $item->unit_price = 850;
    array_push($carrito, $item);

    $preferencias = new MercadoPago\Preference();
    $preferencias->items = $carrito;

    $preferencias->back_urls = array(
        "success" => $pdoconnect->get_ruta() . 'plan/procesar.php',
        "failure" => $pdoconnect->get_ruta() . 'plan/procesar.php',
        "pending" => $pdoconnect->get_ruta() . 'plan/procesar.php'
    );

    $preferencias->auto_return = "approved";
    $preferencias->save();
}

?>

<div class="row h-100 justify-content-center align-items-center mb-5">

    <div class="inicio">
        <div class="card-body">

            <div class="icon">
                <ion-icon name="globe-outline"></ion-icon>
            </div>
            <h2 class="card-title text-center">Gratis</h2>
            <div class="plan">
                <ul class="list-group w-100">
                    <li class="list-group-item">Podes crear hasta tres grupos</li>
                    <li class="list-group-item">Podes invitar hasta tres usuarios</li>
                </ul>
            </div>

        </div>
    </div>

    <div class="inicio mb-5">

        <div class="card-body">
            <div class="icon">
                <ion-icon name="diamond-outline"></ion-icon>
            </div>
            <h2 class="card-title text-center mt-3">Premium $ 850</h2>
            <div class="plan">
                <ul class="list-group w-auto">
                    <li class="list-group-item">Sin limite para crear grupos</li>
                    <li class="list-group-item">Podes invitar todos los usuarios que quieras</li>
                </ul>
                <?php if ($permiso > 0) : ?>
                    <a href="<?php echo $preferencias->init_point; ?>" id="pago" class="w-100 g-2 btn btn-primary mt-3"> <i class="far fa-credit-card"></i> Comprar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<?php if ($permiso > 0) : ?>
    <script src="https://www.mercadopago.com/v2/security.js" view="checkout"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        try {
            var checkout = [];
            var mp = new MercadoPago('TEST-bc6f83b6-5eb1-45dd-b19c-4871f520344a', {
                locale: 'en-US'
            });
            checkout = mp.checkout({
                preferencias: {
                    id: '<?php echo $preferencias->id; ?>'
                }
            });
        } catch (error) {

        }
    </script>
<?php endif; ?>