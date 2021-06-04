<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';

if (!ControlSesion::sesionIniciada() || $_SESSION['estado'] != 'Doctor' || $_SESSION['id'] != $cita->getIdDoctor()) {
    header('Location: ' . RUTA_CITAS, true, 301);
    exit();
}

if (isset($_POST['atender'])) {
    Conexion::abrirConexion();
    RepositorioCita::atenderCita(Conexion::obtenerConexion(), $cita->getId(), $_POST['valoracion']);
    Conexion::cerrarConexion();
    header('Location: ' . SERVIDOR, true, 301);
    exit();
}

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container-small uk-align-center">
    <div class="uk-card uk-card-default">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>ATENDER CITA</b></h3>
        </div>
        <form method="post" action="<?php echo RUTA_CITA . '/' . $cita->getId() ?>">
            <div class="uk-card-body">
                <label>Nombre
                    <input class="uk-input" value="<?php echo $paciente->getNombre() ?>" disabled>
                </label>
                <br><br>
                <label>Documento
                    <input class="uk-input" value="<?php echo $paciente->getDocumento() ?>" disabled>
                </label>
                <br><br>
                <label>Teléfono
                    <input class="uk-input" value="<?php echo $paciente->getTelefono() ?>" disabled>
                </label>
                <br><br>
                <label>Correo electrónico
                    <input class="uk-input" value="<?php echo $paciente->getEmail() ?>" disabled>
                </label>
                <br><br>
                <label>Dirección de residencia
                    <input class="uk-input" value="<?php echo $paciente->getDireccion() ?>" disabled>
                </label>
                <br><br>
                <label>Valoración
                    <textarea name="valoracion" class="uk-textarea" rows="10" required></textarea>
                </label>
            </div>
            <div class="uk-card-footer">
                <button name="atender" type="submit" class="uk-button uk-button-primary uk-width-1-1"><b>ATENDER</b>
                </button>
            </div>
        </form>
    </div>
</div>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
