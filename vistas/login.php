<?php
include_once 'app/Conexion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/IPS.inc.php';
include_once 'app/Doctor.inc.php';
include_once 'app/Paciente.inc.php';
include_once 'app/RepositorioIPS.inc.php';
include_once 'app/ControlSesion.inc.php';

if (ControlSesion::sesionIniciada()) {
    header('Location: ' . SERVIDOR, true, 301);
    exit();
}

$usuario = "";
$tipo = '';
if (isset($_POST['login'])) {
    Conexion::abrirConexion();
    $conexion = Conexion::obtenerConexion();
    if (isset($conexion)) {
        try {
            $sql = "SELECT * FROM ips WHERE email = :email";
            $setencia = $conexion->prepare($sql);
            $setencia->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
            $setencia->execute();
            $resultado = $setencia->fetch();
            if (!empty($resultado)) {
                $usuario = new IPS($resultado['id'], $resultado['nombre'], $resultado['nit'], $resultado['email'], $resultado['password'], $resultado['direccion'], $resultado['fecha_registro']);
                $tipo = 'IPS';
            } else {
                $sql = "SELECT * FROM doctores WHERE email = :email AND estado = 0";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Doctor($resultado['id'], $resultado['nombre'], $resultado['documento'], $resultado['telefono'], $resultado['email'], $resultado['password'], $resultado['direccion'], $resultado['especialidad'], $resultado['fecha_registro'], $resultado['id_ips'], $resultado['estado']);
                    $tipo = 'Doctor';
                } else {
                    $sql = "SELECT * FROM pacientes WHERE email = :email AND estado = 0";
                    $setencia = $conexion->prepare($sql);
                    $setencia->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                    $setencia->execute();
                    $resultado = $setencia->fetch();
                    if (!empty($resultado)) {
                        $usuario = new Paciente($resultado['id'], $resultado['nombre'], $resultado['documento'], $resultado['telefono'], $resultado['email'], $resultado['password'], $resultado['direccion'], $resultado['fecha_registro'], $resultado['id_ips'], $resultado['estado']);
                        $tipo = 'Paciente';
                    }
                }
            }

        } catch (PDOException $ex) {
            print "ERROR" . $ex->getMessage();
        }
    }
}

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container">
    <?php
    if (isset($_POST['login'])) {
        if ($usuario == "" || !password_verify($_POST['password'], $usuario->getPassword())) {
            ?>
            <div class="uk-alert-danger uk-width-1-2@m uk-align-center" uk-alert>
                <p>El correo electrónico y/o la contraseña que ingresaste no coinciden con nuestros registros. Por
                    favor, revisa los datos y vuelve a intentarlo.</p>
            </div>
            <?php
        } else {
            ControlSesion::iniciarSesion($usuario->getId(), $usuario->getNombre(), $tipo);
            if ($tipo == 'Paciente') {
                header('Location: ' . RUTA_CITAS, true, 301);
                exit();
            } elseif ($tipo == 'Doctor') {
                header('Location: ' . RUTA_CITAS, true, 301);
                exit();
            } elseif ($tipo == 'IPS') {
                header('Location: ' . RUTA_RESUMEN, true, 301);
                exit();
            }
        }
    }
    ?>
    <form class="uk-card uk-card-default uk-width-1-2@m uk-align-center" method="post"
          action="<?php echo RUTA_LOGIN ?>">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b>INICIO DE SESIÓN</b></h3>
        </div>
        <div class="uk-card-body">
            <label>Correo electrónico <span style="color: red">*</span>
                <input name="email" type="email" class="uk-input" required>
            </label>
            <br><br>
            <label>Contraseña <span style="color: red">*</span>
                <input name="password" type="password" class="uk-input" required>
            </label>
        </div>
        <div class="uk-card-footer">
            <button name="login" type="submit" class="uk-button uk-button-default uk-width-1-1"><b>INICIAR</b>
            </button>
        </div>
    </form>
</div>
<br>
<?php
include_once 'platillas/doc-cierre.inc.php'
?>
