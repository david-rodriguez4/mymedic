<?php
include_once 'app/Escritor.inc.php';
include_once 'app/RepositorioPaciente.inc.php';
include_once 'app/Paciente.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';

if (ControlSesion::sesionIniciada() && $_SESSION['estado'] == 'IPS') {
    $existe_pac = false;
    $coincide_pac = true;
    if (isset($_POST['registrar_pac'])) {
        Conexion::abrirConexion();
        $existe_pac = RepositorioPaciente::pacienteExiste(Conexion::obtenerConexion(), $_POST['email_pac'], $_POST['documento_pac']);
        if ($existe_pac) {
            ?>
            <div class="uk-alert-danger uk-align-center uk-width-1-2" uk-alert>
                <p>El correo electrónico o el documento ya se encuentra registrado.</p>
            </div>
            <?php
        }

        if ($_POST['password_pac'] !== $_POST['password2_pac']) {
            $coincide_doc = false;
            ?>
            <div class="uk-alert-danger uk-align-center uk-width-1-2" uk-alert>
                <p>Las contraseñas no coinciden.</p>
            </div>
            <?php
        } elseif (!$existe_pac && $coincide_pac) {
            $id_pac = md5(password_hash(rand(0, 100000), PASSWORD_DEFAULT));
            $paciente = new Paciente($id_pac, $_POST['nombre_pac'], $_POST['documento_pac'], $_POST['telefono_pac'], $_POST['email_pac'], password_hash($_POST['password_pac'], PASSWORD_DEFAULT), $_POST['direccion_pac'], '', $_SESSION['id'], '');
            RepositorioPaciente::insertarPaciente(Conexion::obtenerConexion(), $paciente);
            ?>
            <script>
                alert("Se ha registrado correctamente.");
            </script>
            <?php
        }
    }
}

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container uk-align-center">
    <div class="uk-card uk-card-default">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>PACIENTES</b></h3>
            <button type="button" uk-toggle="target: #modal-create"
                    class="uk-button uk-button-danger completada uk-button-small uk-align-right"><i
                        class="fa fa-plus"
                        aria-hidden="true"></i></button>
        </div>
        <div class="uk-card-body">
            <table class="uk-table uk-table-hover uk-table-divider">
                <thead>
                <tr>
                    <th><b>Nombre</b></th>
                    <th><b>Documento</b></th>
                    <th><b>Teléfono</b></th>
                    <th><b>Correo electrónico</b></th>
                    <th><b>Dirección</b></th>
                    <th><b>Eliminar</b></th>
                </tr>
                </thead>
                <tbody>
                <?php
                Escritor::escribirTodosPacientes();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="modal-create" method="post" action="<?php echo RUTA_PACIENTES ?>" uk-modal>
    <div class="uk-card uk-card-default uk-width-1-2 uk-align-center">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b>AÑADIR PACIENTE</b></h3>
        </div>
        <div class="uk-card-body">
            <label>Nombre <span style="color: red">*</span>
                <input name="nombre_pac" type="text" class="uk-input" required>
            </label>
            <br><br>
            <label>Documento <span style="color: red">*</span>
                <input name="documento_pac" type="number" class="uk-input" required>
            </label>
            <br><br>
            <label>Teléfono <span style="color: red">*</span>
                <input name="telefono_pac" type="number" class="uk-input" required>
            </label>
            <br><br>
            <label>Correo electrónico <span style="color: red">*</span>
                <input name="email_pac" type="email" class="uk-input" required>
            </label>
            <br><br>
            <label>Contraseña <span style="color: red">*</span>
                <input name="password_pac" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Confirmar contraseña <span style="color: red">*</span>
                <input name="password2_pac" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Dirección <span style="color: red">*</span>
                <input name="direccion_pac" type="text" class="uk-input" required>
            </label>
        </div>
        <div class="uk-card-footer">
            <button name="registrar_pac" type="submit" class="uk-button uk-button-primary uk-width-1-1">
                <b>REGISTRAR</b>
            </button>
            <br><br>
            <button class="uk-button uk-button-danger uk-width-1-1 uk-modal-close" type="button"><b>CANCELAR</b>
            </button>
        </div>
    </div>
</form>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
