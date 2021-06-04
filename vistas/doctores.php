<?php
include_once 'app/Escritor.inc.php';
include_once 'app/RepositorioDoctor.inc.php';
include_once 'app/Doctor.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';

if ($_SESSION['estado'] != 'IPS') {
    header('Location: ' . RUTA_CITAS, true, 301);
    exit();
}

$existe_doc = false;
$coincide_doc = true;
$especialidad = '';
if (isset($_POST['registrar_doc'])) {
    Conexion::abrirConexion();
    $existe_doc = RepositorioDoctor::doctorExiste(Conexion::obtenerConexion(), $_POST['email_doc'], $_POST['documento_doc']);
    if ($existe_doc) {
        ?>
        <div class="uk-alert-danger uk-align-center uk-width-1-2" uk-alert>
            <p>El correo electrónico o el documento ya se encuentra registrado.</p>
        </div>
        <?php
    }

    if ($_POST['especialidad'] == 0) {
        ?>
        <div class="uk-alert-danger uk-align-center uk-width-1-2" uk-alert>
            <p>Seleccione una especialidad válida.</p>
        </div>
        <?php
    } elseif ($_POST['especialidad'] == 1) {
        $especialidad = 'Medicina general';
    } elseif ($_POST['especialidad'] == 2) {
        $especialidad = 'Terapia física';
    }

    if ($_POST['password_doc'] !== $_POST['password2_doc']) {
        $coincide_doc = false;
        ?>
        <div class="uk-alert-danger uk-align-center uk-width-1-2" uk-alert>
            <p>Las contraseñas no coinciden.</p>
        </div>
        <?php
    } elseif (!$existe_doc && $coincide_doc && ($_POST['especialidad'] !== 0)) {
        $id_doc = md5(password_hash(rand(0, 100000), PASSWORD_DEFAULT));
        $doctor = new Doctor($id_doc, $_POST['nombre_doc'], $_POST['documento_doc'], $_POST['telefono_doc'], $_POST['email_doc'], password_hash($_POST['password_doc'], PASSWORD_DEFAULT), $_POST['direccion_doc'], $especialidad, '', $_SESSION['id'], '');
        RepositorioDoctor::insertarDoctor(Conexion::obtenerConexion(), $doctor);
        ?>
        <script>
            alert("Se ha registrado correctamente.");
        </script>
        <?php
    }
}
?>
<br>
<div class="uk-container uk-align-center">
    <div class="uk-card uk-card-default">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>DOCTORES</b></h3>
            <button type="button" uk-toggle="target: #modal-create"
                    class="uk-button uk-button-danger completada uk-button-small uk-align-right"><i
                        class="fa fa-plus" aria-hidden="true"></i></button>
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
                    <th><b>Especialidad</b></th>
                    <th><b>Eliminar</b></th>
                </tr>
                </thead>
                <tbody>
                <?php
                Escritor::escribirTodosDoctores();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="modal-create" method="post" action="<?php echo RUTA_DOCTORES ?>" uk-modal>
    <div class="uk-card uk-card-default uk-width-1-2 uk-align-center">
        <div class="uk-card-header">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b>AÑADIR DOCTOR</b></h3>
        </div>
        <div class="uk-card-body">
            <label>Nombre <span style="color: red">*</span>
                <input name="nombre_doc" type="text" class="uk-input" required>
            </label>
            <br><br>
            <label>Documento <span style="color: red">*</span>
                <input name="documento_doc" type="number" class="uk-input" required>
            </label>
            <br><br>
            <label>Teléfono <span style="color: red">*</span>
                <input name="telefono_doc" type="number" class="uk-input" required>
            </label>
            <br><br>
            <label>Correo electrónico <span style="color: red">*</span>
                <input name="email_doc" type="email" class="uk-input" required>
            </label>
            <br><br>
            <label>Contraseña <span style="color: red">*</span>
                <input name="password_doc" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Confirmar contraseña <span style="color: red">*</span>
                <input name="password2_doc" type="password" class="uk-input" required>
            </label>
            <br><br>
            <label>Dirección <span style="color: red">*</span>
                <input name="direccion_doc" type="text" class="uk-input" required>
            </label>
            <br><br>
            <label>Especialidad <span style="color: red">*</span>
                <select class="uk-select" name="especialidad" required>
                    <option value="0">Selecciona una...</option>
                    <option value="1">Medicina general</option>
                    <option value="2">Terapia física</option>
                </select>
            </label>
        </div>
        <div class="uk-card-footer">
            <button name="registrar_doc" type="submit" class="uk-button uk-button-primary uk-width-1-1"><b>REGISTRAR</b>
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
