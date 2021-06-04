<?php
include_once 'app/Conexion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/IPS.inc.php';
include_once 'app/RepositorioIPS.inc.php';
include_once 'app/ControlSesion.inc.php';

if (ControlSesion::sesionIniciada()) {
    header('Location: ' . SERVIDOR, true, 301);
    exit();
}

$existe = false;
$coincide = false;
if (isset($_POST['registrar'])) {
    Conexion::abrirConexion();
    $existe = RepositorioIPS::ipsExiste(Conexion::obtenerConexion(), $_POST['email'], $_POST['nit']);
    if ($_POST['password'] == $_POST['password2']) {
        $coincide = true;
    }
}

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container">
    <?php
    if (isset($_POST['registrar'])) {
        if ($existe) {
            ?>
            <div class="uk-alert-danger uk-width-1-2@m uk-align-center" uk-alert>
                <p>El correo electrónico o el NIT ya se encuentra registrado.</p>
            </div>
            <?php
        }
        if ($coincide == false) {
            ?>
            <div class="uk-alert-danger uk-width-1-2@m uk-align-center" uk-alert>
                <p>Las contraseñas no coinciden.</p>
            </div>
            <?php
        } elseif (!$existe && $coincide) {
            $id = md5(password_hash(rand(0, 100000), PASSWORD_DEFAULT));
            $IPS = new IPS($id, $_POST['nombre'], $_POST['nit'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['direccion'], '');
            RepositorioIPS::insertarIPS(Conexion::obtenerConexion(), $IPS);
            header('Location: ' . RUTA_REGISTRO_CORRECTO, true, 301);
            exit();
        }
    }
    ?>
    <form class="uk-card uk-card-default uk-width-1-2@m uk-align-center" method="post"
          action="<?php echo RUTA_REGISTRO_IPS ?>">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b>REGISTRO</b></h3>
        </div>
        <div class="uk-card-body">
            <label>Nombre <span style="color: red">*</span>
                <input name="nombre" type="text" class="uk-input" required>
            </label>
            <br><br>
            <label>NIT <span style="color: red">*</span>
                <input name="nit" type="text" class="uk-input" required>
            </label>
            <br><br>
            <label>Correo electrónico <span style="color: red">*</span>
                <input name="email" type="email" class="uk-input" required>
            </label>
            <br><br>
            <label>Contraseña <span style="color: red">*</span>
                <input name="password" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Confirmar contraseña <span style="color: red">*</span>
                <input name="password2" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Dirección <span style="color: red">*</span>
                <input name="direccion" type="text" class="uk-input" required>
            </label>
        </div>
        <div class="uk-card-footer">
            <button name="registrar" type="submit" class="uk-button uk-button-default uk-width-1-1"><b>REGISTRAR</b>
            </button>
        </div>
    </form>
</div>
<br>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
