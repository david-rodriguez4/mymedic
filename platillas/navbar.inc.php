<?php
include_once 'app/ControlSesion.inc.php';
?>

<nav class="uk-navbar-container" uk-navbar>

    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo" href="<?php echo SERVIDOR ?>"><img src="<?php echo RUTA_IMG ?>logomymedic.png"
                                                                             width="40" height="40" alt="Logo"><b>MyMedic</b></a>
    </div>
    <?php
    if (!ControlSesion::sesionIniciada()) {
        ?>
        <div class="uk-navbar-right">
            <div class="uk-navbar-item">
                <a href="<?php echo RUTA_LOGIN ?>" class="uk-button uk-button-primary"><b>Iniciar sesión</b></a>
            </div>
            <div class="uk-navbar-item">
                <a href="<?php echo RUTA_REGISTRO_IPS ?>" class="uk-button uk-button-secondary"><b>Registrarse</b></a>
            </div>
        </div>
        <?php
    } else {
        if ($_SESSION['estado'] == 'IPS') {
            ?>
            <div class="uk-navbar-container uk-navbar-left" uk-navbar>
                <ul class="uk-navbar-nav">
                    <li><a href="<?php echo RUTA_RESUMEN ?>"><b>RESUMEN</b></a></li>
                    <li><a href="<?php echo RUTA_CITAS ?>"><b>CITAS</b></a></li>
                    <li><a href="<?php echo RUTA_DOCTORES ?>"><b>DOCTORES</b></a></li>
                    <li><a href="<?php echo RUTA_PACIENTES ?>"><b>PACIENTES</b></a></li>
                </ul>
            </div>
            <?php
        } else {
            ?>
            <div class="uk-navbar-container uk-navbar-left" uk-navbar>
                <ul class="uk-navbar-nav">
                    <li><a href="<?php echo RUTA_CITAS ?>"><b>CITAS</b></a></li>
                </ul>
            </div>
            <?php
        }
        ?>
        <div class="uk-navbar-right">
            <div class="uk-navbar-item">
                <a href="<?php echo RUTA_LOGOUT ?>" class="uk-button uk-button-danger"><b>Cerrar sesión</b></a>
            </div>
        </div>
        <?php
    }
    ?>
</nav>