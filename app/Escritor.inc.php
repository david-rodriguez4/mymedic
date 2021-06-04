<?php
include_once 'app/IPS.inc.php';
include_once 'app/Doctor.inc.php';
include_once 'app/RepositorioIPS.inc.php';
include_once 'app/RepositorioDoctor.inc.php';
include_once 'app/RepositorioPaciente.inc.php';
include_once 'app/RepositorioCita.inc.php';
include_once 'app/Paciente.inc.php';
include_once 'app/Cita.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redirigir.inc.php';

class Escritor
{
    public static function escribirTodasCitas()
    {
        Conexion::abrirConexion();
        $citas = RepositorioCita::obtenerCitas(Conexion::obtenerConexion());
        if (count($citas)) {
            foreach ($citas as $cita) {
                self::escribirCita($cita);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function escribirCita($cita)
    {
        if (!isset($cita)) {
            return;
        } else {
            $paciente = RepositorioPaciente::obtenerPacienteId(Conexion::obtenerConexion(), $cita->getIdPaciente());
            $doctor = RepositorioDoctor::obtenerDoctorId(Conexion::obtenerConexion(), $cita->getIdDoctor());
        }

        if (isset($_POST[$cita->getId()])) {
            RepositorioCita::desactivarCita(Conexion::obtenerConexion(), $cita->getId());
            ?>
            <script>
                window.location.replace("http://localhost/mymedic/citas");
            </script>
            <?php
        }
        ?>
        <tr>
            <td><?php echo $paciente->getNombre() ?></td>
            <td><?php echo $doctor->getNombre() ?></td>
            <td><?php echo $doctor->getEspecialidad() ?></td>
            <td><?php echo $cita->getFechaAtencion() ?></td>
            <?php
            if ($cita->getEstado() == 0) {
                ?>
                <td><span class="uk-badge agendada"><b>Agendada</b></span></td>
                <?php
            } elseif ($cita->getEstado() == 1) {
                ?>
                <td><span class="uk-badge completada"><b>Completada</b></span></td>
                <?php
            } elseif ($cita->getEstado() == 2) {
                ?>
                <td><span class="uk-badge cancelada"><b>Cancelada</b></span></td>
                <?php
            }
            if ($cita->getEstado() == 0) {
                ?>
                <td>
                    <form method="post" action="<?php echo RUTA_CITAS ?>">
                        <button type="submit" name="<?php echo $cita->getId() ?>"
                                class="uk-button uk-button-danger uk-button-small"><i class="fa fa-times"
                                                                                      aria-hidden="true"></i></button>
                    </form>
                </td>
                <?php
            } else {
                ?>
                <td></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }

    public static function escribirTodosDoctores()
    {
        Conexion::abrirConexion();
        $doctores = RepositorioDoctor::obtenerDoctores(Conexion::obtenerConexion());
        if (count($doctores)) {
            foreach ($doctores as $doctor) {
                self::escribirDoctor($doctor);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function escribirDoctor($doctor)
    {
        if (!isset($doctor)) {
            return;
        }

        if (isset($_POST[$doctor->getId()])) {
            RepositorioDoctor::desactivarDoctor(Conexion::obtenerConexion(), $doctor->getId());
            ?>
            <script>
                window.location.replace("http://localhost/mymedic/doctores");
            </script>
            <?php
        }

        if ($doctor->getEstado() == 0) {
            ?>
            <tr>
                <td><?php echo $doctor->getNombre() ?></td>
                <td><?php echo $doctor->getDocumento() ?></td>
                <td><?php echo $doctor->getTelefono() ?></td>
                <td><?php echo $doctor->getEmail() ?></td>
                <td><?php echo $doctor->getDireccion() ?></td>
                <td><?php echo $doctor->getEspecialidad() ?></td>
                <td>
                    <form method="post" action="<?php echo RUTA_DOCTORES ?>">
                        <button type="submit" name="<?php echo $doctor->getId() ?>"
                                class="uk-button uk-button-danger uk-button-small"><i class="fa fa-times"
                                                                                      aria-hidden="true"></i></button>
                    </form>
                </td>
            </tr>
            <?php
        }
    }

    public static function escribirTodosPacientes()
    {
        Conexion::abrirConexion();
        $pacientes = RepositorioPaciente::obtenerPacientes(Conexion::obtenerConexion());
        if (count($pacientes)) {
            foreach ($pacientes as $paciente) {
                self::escribirPaciente($paciente);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function escribirPaciente($paciente)
    {
        if (!isset($paciente)) {
            return;
        }

        if (isset($_POST[$paciente->getId()])) {
            RepositorioPaciente::desactivarPaciente(Conexion::obtenerConexion(), $paciente->getId());
            ?>
            <script>
                window.location.replace("http://localhost/mymedic/usuarios");
            </script>
            <?php
        }

        if ($paciente->getEstado() == 0) {
            ?>
            <tr>
                <td><?php echo $paciente->getNombre() ?></td>
                <td><?php echo $paciente->getDocumento() ?></td>
                <td><?php echo $paciente->getTelefono() ?></td>
                <td><?php echo $paciente->getEmail() ?></td>
                <td><?php echo $paciente->getDireccion() ?></td>
                <td>
                    <form method="post" action="<?php echo RUTA_PACIENTES ?>">
                        <button name="<?php echo $paciente->getId() ?>"
                                class="uk-button uk-button-danger uk-button-small"><i class="fa fa-times"
                                                                                      aria-hidden="true"></i></button>
                    </form>
                </td>
            </tr>
            <?php
        }
    }

    public static function seleccionarPacientes()
    {
        Conexion::abrirConexion();
        $pacientes = RepositorioPaciente::obtenerPacientes(Conexion::obtenerConexion());
        if (count($pacientes)) {
            foreach ($pacientes as $paciente) {
                self::seleccionarPaciente($paciente);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function seleccionarPaciente($paciente)
    {
        if (!isset($paciente)) {
            return;
        }
        ?>
        <option value="<?php echo $paciente->getId() ?>"><?php echo $paciente->getNombre() ?> -
            CC: <?php echo $paciente->getDocumento() ?></option>
        <?php
    }

    public static function seleccionarDoctores()
    {
        Conexion::abrirConexion();
        $doctores = RepositorioDoctor::obtenerDoctores(Conexion::obtenerConexion());
        if (count($doctores)) {
            foreach ($doctores as $doctor) {
                self::seleccionarDoctor($doctor);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function seleccionarDoctor($doctor)
    {
        if (!isset($doctor)) {
            return;
        }

        ?>
        <option value="<?php echo $doctor->getId() ?>"><?php echo $doctor->getNombre() ?>
            - <?php echo $doctor->getEspecialidad() ?></option>
        <?php
    }

    public static function escribirMisCitas()
    {
        Conexion::abrirConexion();
        $citas = RepositorioCita::obtenerCitasIdPaciente(Conexion::obtenerConexion(), $_SESSION['id']);
        if (count($citas)) {
            foreach ($citas as $cita) {
                self::escribirCita($cita);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function escribirLasCitas()
    {
        Conexion::abrirConexion();
        $citas = RepositorioCita::obtenerCitasIdDoctor(Conexion::obtenerConexion(), $_SESSION['id']);
        if (count($citas)) {
            foreach ($citas as $cita) {
                self::escribirLaCita($cita);
            }
        }
        Conexion::cerrarConexion();
    }

    public static function escribirLaCita($cita)
    {
        if (!isset($cita)) {
            return;
        } else {
            $paciente = RepositorioPaciente::obtenerPacienteId(Conexion::obtenerConexion(), $cita->getIdPaciente());
            $doctor = RepositorioDoctor::obtenerDoctorId(Conexion::obtenerConexion(), $cita->getIdDoctor());
        }
        ?>
        <tr>
            <td><?php echo $paciente->getNombre() ?></td>
            <td><?php echo $doctor->getNombre() ?></td>
            <td><?php echo $doctor->getEspecialidad() ?></td>
            <td><?php echo $cita->getFechaAtencion() ?></td>
            <?php
            if ($cita->getEstado() == 0) {
                ?>
                <td><span class="uk-badge agendada"><b>Agendada</b></span></td>
                <?php
            } elseif ($cita->getEstado() == 1) {
                ?>
                <td><span class="uk-badge completada"><b>Completada</b></span></td>
                <?php
            } elseif ($cita->getEstado() == 2) {
                ?>
                <td><span class="uk-badge cancelada"><b>Cancelada</b></span></td>
                <?php
            }
            if ($cita->getEstado() == 0) {
                ?>
                <td>
                    <a href="<?php echo RUTA_CITA . '/' . $cita->getId() ?>" class="uk-button uk-button-danger completada uk-button-small"><i class="fa fa-check" aria-hidden="true"></i></a>
                </td>
                <?php
            } else {
                ?>
                <td></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }
}