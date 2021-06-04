<?php
include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
if (ControlSesion::sesionIniciada()) {
    if ($_SESSION['estado'] == 'IPS') {
        header('Location: ' . RUTA_RESUMEN, true, 301);
        exit();
    } elseif ($_SESSION['estado'] == 'Doctor') {
        header('Location: ' . RUTA_CITAS, true, 301);
        exit();
    } elseif ($_SESSION['estado'] == 'Paciente') {
        header('Location: ' . RUTA_CITAS, true, 301);
        exit();
    }
}
?>
<br>
<div class="uk-container-small uk-align-center">
    <a class="uk-navbar-item uk-logo"><img src="<?php echo RUTA_IMG ?>logomymedic.png" alt="Logo"><h3 style="font-size: 50px"><b>MyMedic</b></h3></a>
</div>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
