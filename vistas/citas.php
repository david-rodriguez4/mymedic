<?php
include_once 'app/Escritor.inc.php';
include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
include_once 'app/RepositorioDoctor.inc.php';
include_once 'app/Doctor.inc.php';
include_once 'app/Cita.inc.php';
include_once 'app/Paciente.inc.php';
include_once 'app/RepositorioCita.inc.php';
include_once 'app/RepositorioPaciente.inc.php';
include_once 'app/Conexion.inc.php';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (!ControlSesion::sesionIniciada()) {
    header('Location: ' . SERVIDOR, true, 301);
    exit();
}

if ($_SESSION['estado'] == 'IPS') {
    if (isset($_POST['agendar'])) {
        if ((strtotime('8:00') < strtotime($_POST['hora'])) && (strtotime($_POST['hora']) < strtotime('17:00'))) {
            $hora = true;
        } else {
            $hora = false;
        }
        Conexion::abrirConexion();
        $fecha_atencion = $_POST['fecha'] . ' ' . $_POST['hora'] . ':00';
        $cita_existe = RepositorioCita::citaExiste(Conexion::obtenerConexion(), $_POST['paciente'], $_POST['doctor'], $fecha_atencion);
        if ($_POST['paciente'] != 0 && $_POST['doctor'] != 0 && !$cita_existe && $hora) {
            $id_cita = md5(password_hash(rand(0, 100000), PASSWORD_DEFAULT));
            $cita = new Cita($id_cita, $_POST['paciente'], $_POST['doctor'], '', '', $fecha_atencion, '');
            RepositorioCita::insertarCita(Conexion::obtenerConexion(), $cita);
            ?>
            <script>
                alert("Se ha agendado la cita correctamente.");
            </script>
            <?php
            $paciente = RepositorioPaciente::obtenerPacienteId(Conexion::obtenerConexion(), $_POST['paciente']);
            $doctor = RepositorioDoctor::obtenerDoctorId(Conexion::obtenerConexion(), $_POST['doctor']);
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = 'true';
            $mail->SMTPSecure = 'tls';
            $mail->Port = '587';
            $mail->Username = 'no.reply.mymedic@gmail.com';
            $mail->Password = 'mymedic123456';
            $mail->Subject = 'Agendamiento de cita - MyMedic';
            $mail->setFrom('no.reply.mymedic@gmail.com');
            $mail->CharSet = 'UTF-8';
            $message = "<html lang='es'><body>";
            $message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
            $message .= "<tr><td>";
            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
            $message .= "<thead>
                            <tr height='80'>
                                <td colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;' >MyMedic</td>
                            </tr>
                         </thead>";
            $message .= "<tbody>
                            <tr>
                                <td colspan='4' style='padding:15px;'>
                                    <p style='font-size:20px;'>Hola " . $paciente->getNombre() . "</p>
                                    <hr />
                                    <p style='font-size:25px;'>Se ha registrado una cita médica.</p>
                                    <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'><b>Doctor: </b>" . $doctor->getNombre() . "</p>
                                    <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'><b>Tipo: </b>" . $doctor->getEspecialidad() . "</p>
                                    <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'><b>Fecha y  hora: </b>" . $cita->getFechaAtencion() . "</p>
                                    <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'>Te recordamos que verifiques tus citas agendadas en la plataforma.</p>
                                </td>
                            </tr>
                         </tbody>";
            $message .= "</table>";
            $message .= "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";
            $mail->Body = $message;
            $mail->addAddress($paciente->getEmail());
            $mail->send();
            $mail->smtpClose();
        } else {
            ?>
            <script>
                alert("Error al agendar la cita.");
            </script>
            <?php
        }
    }

    ?>
    <br>
    <div class="uk-container uk-align-center">
        <div class="uk-card uk-card-default">
            <div class="uk-card-header">
                <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>CITAS</b></h3>
                <button type="button" uk-toggle="target: #modal-create"
                        class="uk-button uk-button-danger completada uk-button-small uk-align-right"><i
                            class="fa fa-plus" aria-hidden="true"></i></button>
            </div>
            <div class="uk-card-body">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                    <tr>
                        <th><b>Paciente</b></th>
                        <th><b>Doctor</b></th>
                        <th><b>Tipo</b></th>
                        <th><b>Fecha</b></th>
                        <th><b>Estado</b></th>
                        <th><b>Cancelar</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    Escritor::escribirTodasCitas();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <form id="modal-create" method="post" action="<?php echo RUTA_CITAS ?>" uk-modal>
        <div class="uk-card uk-card-default uk-width-1-2 uk-align-center">
            <div class="uk-card-header">
                <h3 class="uk-card-title uk-margin-remove-bottom"><b>AGENDAR CITA</b></h3>
            </div>
            <div class="uk-card-body">
                <label>Paciente <span style="color: red">*</span>
                    <select name="paciente" class="uk-select" required>
                        <option value="0">Selecciona uno...</option>
                        <?php
                        Escritor::seleccionarPacientes();
                        ?>
                    </select>
                </label>
                <br><br>
                <label>Doctor <span style="color: red">*</span>
                    <select name="doctor" class="uk-select" required>
                        <option value="0">Selecciona uno...</option>
                        <?php
                        Escritor::seleccionarDoctores();
                        ?>
                    </select>
                </label>
                <br><br>
                <label>Fecha <span style="color: red">*</span>
                    <input name="fecha" type="date" class="uk-input" min="<?php echo date("Y-m-d") ?>" required>
                </label>
                <br><br>
                <label>Hora <span style="color: red">*</span>
                    <input name="hora" type="time" class="uk-input" required>
                </label>
            </div>
            <div class="uk-card-footer">
                <button name="agendar" type="submit" class="uk-button uk-button-primary uk-width-1-1">
                    <b>AGENDAR</b>
                </button>
                <br><br>
                <button class="uk-button uk-button-danger uk-width-1-1 uk-modal-close" type="button"><b>CANCELAR</b>
                </button>
            </div>
        </div>
    </form>
    <?php
} elseif ($_SESSION['estado'] == 'Paciente') {
    ?>
    <br>
    <div class="uk-container uk-align-center">
        <div class="uk-card uk-card-default">
            <div class="uk-card-header">
                <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>MIS CITAS</b></h3>
            </div>
            <div class="uk-card-body">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                    <tr>
                        <th><b>Paciente</b></th>
                        <th><b>Doctor</b></th>
                        <th><b>Tipo</b></th>
                        <th><b>Fecha</b></th>
                        <th><b>Estado</b></th>
                        <th><b>Cancelar</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    Escritor::escribirMisCitas();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
} elseif ($_SESSION['estado'] == 'Doctor') {
    ?>
    <br>
    <div class="uk-container uk-align-center">
        <div class="uk-card uk-card-default">
            <div class="uk-card-header">
                <h3 class="uk-card-title uk-margin-remove-bottom uk-align-left"><b>CITAS</b></h3>
            </div>
            <div class="uk-card-body">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                    <tr>
                        <th><b>Paciente</b></th>
                        <th><b>Doctor</b></th>
                        <th><b>Tipo</b></th>
                        <th><b>Fecha</b></th>
                        <th><b>Estado</b></th>
                        <th><b>Atender</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    Escritor::escribirLasCitas();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>