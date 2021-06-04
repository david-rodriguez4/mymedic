<?php
include_once 'app/Doctor.inc.php';

class RepositorioDoctor
{
    public static function doctorExiste($conexion, $email, $documento)
    {
        $existe = true;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM doctores WHERE email = :email OR documento = :documento";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':email', $email, PDO::PARAM_STR);
                $setencia->bindParam(':documento', $documento, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    $existe = true;
                } else {
                    $existe = false;
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $existe;
    }

    public static function insertarDoctor($conexion, $doctor)
    {
        $insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO doctores(id, nombre, documento, telefono, email, password, direccion, especialidad, fecha_registro, id_ips, estado) VALUES(:id, :nombre, :documento, :telefono, :email, :password, :direccion, :especialidad, NOW(), :id_ips, '0')";
                $setencia = $conexion->prepare($sql);

                $idtemp = $doctor->getId();
                $nombretemp = $doctor->getNombre();
                $documentotemp = $doctor->getDocumento();
                $telefonotemp = $doctor->getTelefono();
                $emailtemp = $doctor->getEmail();
                $passwordtemp = $doctor->getPassword();
                $direcciontemp = $doctor->getDireccion();
                $especialidadtemp = $doctor->getEspecialidad();
                $idipstemp = $doctor->getIdIps();

                $setencia->bindParam(':id', $idtemp, PDO::PARAM_STR);
                $setencia->bindParam(':nombre', $nombretemp, PDO::PARAM_STR);
                $setencia->bindParam(':documento', $documentotemp, PDO::PARAM_STR);
                $setencia->bindParam(':telefono', $telefonotemp, PDO::PARAM_STR);
                $setencia->bindParam(':email', $emailtemp, PDO::PARAM_STR);
                $setencia->bindParam(':password', $passwordtemp, PDO::PARAM_STR);
                $setencia->bindParam(':direccion', $direcciontemp, PDO::PARAM_STR);
                $setencia->bindParam(':especialidad', $especialidadtemp, PDO::PARAM_STR);
                $setencia->bindParam(':id_ips', $idipstemp, PDO::PARAM_STR);
                $insertado = $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $insertado;
    }

    public static function obtenerDoctores($conexion)
    {
        $doctores = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM doctores";
                $setencia = $conexion->prepare($sql);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $doctores[] = new Doctor($fila['id'], $fila['nombre'], $fila['documento'], $fila['telefono'], $fila['email'], $fila['password'], $fila['direccion'], $fila['especialidad'], $fila['fecha_registro'], $fila['id_ips'], $fila['estado']);
                    }
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $doctores;
    }

    public static function obtenerDoctorId($conexion, $id)
    {
        $doctor = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM doctores WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetch();
                if (count($resultado)) {
                    $doctor = new Doctor($resultado['id'], $resultado['nombre'], $resultado['documento'], $resultado['telefono'], $resultado['email'], $resultado['password'], $resultado['direccion'], $resultado['especialidad'], $resultado['fecha_registro'], $resultado['id_ips'], $resultado['estado']);
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $doctor;
    }

    public static function desactivarDoctor($conexion, $id)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE doctores SET estado = 1 WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return true;
    }
}