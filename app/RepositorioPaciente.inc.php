<?php
include_once 'app/Paciente.inc.php';

class RepositorioPaciente
{
    public static function pacienteExiste($conexion, $email, $documento)
    {
        $existe = true;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM pacientes WHERE email = :email OR documento = :documento";
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

    public static function insertarPaciente($conexion, $paciente)
    {
        $insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO pacientes(id, nombre, documento, telefono, email, password, direccion, fecha_registro, id_ips, estado) VALUES(:id, :nombre, :documento, :telefono, :email, :password, :direccion, NOW(), :id_ips, '0')";
                $setencia = $conexion->prepare($sql);

                $idtemp = $paciente->getId();
                $nombretemp = $paciente->getNombre();
                $documentotemp = $paciente->getDocumento();
                $telefonotemp = $paciente->getTelefono();
                $emailtemp = $paciente->getEmail();
                $passwordtemp = $paciente->getPassword();
                $direcciontemp = $paciente->getDireccion();
                $idipstemp = $paciente->getIdIps();

                $setencia->bindParam(':id', $idtemp, PDO::PARAM_STR);
                $setencia->bindParam(':nombre', $nombretemp, PDO::PARAM_STR);
                $setencia->bindParam(':documento', $documentotemp, PDO::PARAM_STR);
                $setencia->bindParam(':telefono', $telefonotemp, PDO::PARAM_STR);
                $setencia->bindParam(':email', $emailtemp, PDO::PARAM_STR);
                $setencia->bindParam(':password', $passwordtemp, PDO::PARAM_STR);
                $setencia->bindParam(':direccion', $direcciontemp, PDO::PARAM_STR);
                $setencia->bindParam(':id_ips', $idipstemp, PDO::PARAM_STR);
                $insertado = $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $insertado;
    }

    public static function obtenerPacientes($conexion)
    {
        $pacientes = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM pacientes";
                $setencia = $conexion->prepare($sql);
                $setencia->execute();
                $resultado = $setencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $pacientes[] = new Paciente($fila['id'], $fila['nombre'], $fila['documento'], $fila['telefono'], $fila['email'], $fila['password'], $fila['direccion'], $fila['fecha_registro'], $fila['id_ips'], $fila['estado']);
                    }
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $pacientes;
    }

    public static function obtenerPacienteId($conexion, $id)
    {
        $paciente = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM pacientes WHERE id = :id";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':id', $id, PDO::PARAM_STR);
                $setencia->execute();
                $resultado = $setencia->fetch();
                if (count($resultado)) {
                    $paciente = new Paciente($resultado['id'], $resultado['nombre'], $resultado['documento'], $resultado['telefono'], $resultado['email'], $resultado['password'], $resultado['direccion'], $resultado['fecha_registro'], $resultado['id_ips'], $resultado['estado']);
                }
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $paciente;
    }

    public static function desactivarPaciente($conexion, $id)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE pacientes SET estado = 1 WHERE id = :id";
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