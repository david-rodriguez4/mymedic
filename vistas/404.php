<?php
header($_SERVER['SERVER_PROTOCOL'] . "404 not found", true, 404);

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container-small uk-align-center">
    <div class="uk-alert-danger uk-text-center" uk-alert>
        <i style="font-size: 10rem" class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        <h1 style="font-size: 5rem">404</h1>
        <h2>Página no encontrada</h2>
        <h2>Oops! La página que estás buscando no existe. Puede que haya sido movida o eliminada.</h2>
    </div>
</div>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
