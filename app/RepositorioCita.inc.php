<?php
include_once 'app/Cita.inc.php';

class RepositorioCita
{

    public static function insertarCita($conexion, $cita)
    {
        $insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO citas(id, id_paciente, id_doctor, fecha_reserva, fecha_atencion, estado) VALUES(:id, :id_paciente, :id_doctor, NOW(), :fecha_atencion, '0')";
                $setencia = $conexion->prepare($sql);

                $idtemp = $cita->getId();
                $idpactemp = $cita->getIdPaciente();
                $iddoctemp = $cita->getIdDoctor();
                $fatemp = $cita->getFechaAtencion();

                $setencia->bindParam(':id', $idtemp, PDO::PARAM_STR);
                $setencia->bindParam(':id_paciente', $idpactemp, PDO::PARAM_STR);
                $setencia->bindParam(':id_doctor', $iddoctemp, PDO::PARAM_STR);
                $setencia->bindParam(':fecha_atencion', $fatemp, PDO::PARAM_STR);
                $insertado = $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $insertado;
    }

    public static function obtenerCitas($conexion)
    {
        $citas = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM citas ORDER BY fecha_atencion ASC";
                $setencia = $conexion->prepare($sql);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $citas[] = new Cita($fila['id'], $fila['id_paciente'], $fila['id_doctor'], $fila['valoracion'], $fila['fecha_reserva'], $fila['fecha_atencion'], $fila['estado']);
                    }
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $citas;
    }

    public static function obtenerCitasIdPaciente($conexion, $id)
    {
        $citas = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM citas WHERE id_paciente = :id_paciente";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id_paciente', $id, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $citas[] = new Cita($fila['id'], $fila['id_paciente'], $fila['id_doctor'], $fila['valoracion'], $fila['fecha_reserva'], $fila['fecha_atencion'], $fila['estado']);
                    }
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $citas;
    }

    public static function obtenerCitasIdDoctor($conexion, $id)
    {
        $citas = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM citas WHERE id_doctor = :id_doctor";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id_doctor', $id, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $citas[] = new Cita($fila['id'], $fila['id_paciente'], $fila['id_doctor'], $fila['valoracion'], $fila['fecha_reserva'], $fila['fecha_atencion'], $fila['estado']);
                    }
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $citas;
    }

    public static function obtenerCitaIdAtender($conexion, $id)
    {
        $cita = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM citas WHERE id = :id AND estado = 0";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetch();
                if (!empty($resultado)) {
                    $cita = new Cita($resultado['id'], $resultado['id_paciente'], $resultado['id_doctor'], $resultado['valoracion'], $resultado['fecha_reserva'], $resultado['fecha_atencion'], $resultado['estado']);
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $cita;
    }

    public static function desactivarCita($conexion, $id)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE citas SET estado = 2 WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return true;
    }

    public static function atenderCita($conexion, $id, $valoracion)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE citas SET valoracion = :valoracion WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->bindParam(':valoracion', $valoracion, PDO::PARAM_STR);
                $setencia->execute();

                $sql = "UPDATE citas SET estado = 1 WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return true;
    }

    public static function citaExiste($conexion, $id_paciente, $id_doctor, $fecha_atencion)
    {
        $cita = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM citas WHERE id_paciente = :id_paciente AND id_doctor = :id_doctor AND fecha_atencion = :fecha_atencion";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id_paciente', $id_paciente, PDO::PARAM_STR);
                $setencia->bindParam(':id_doctor', $id_doctor, PDO::PARAM_STR);
                $setencia->bindParam(':fecha_atencion', $fecha_atencion, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetch();
                if (!empty($resultado)) {
                    $cita = true;
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $cita;
    }
}