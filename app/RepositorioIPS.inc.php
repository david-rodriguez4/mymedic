<?php
include_once 'app/IPS.inc.php';

class RepositorioIPS
{
    public static function ipsExiste($conexion, $email, $nit)
    {
        $existe = true;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM ips WHERE email = :email OR nit = :nit";
                $setencia = $conexion->prepare($sql);
                $setencia->bindParam(':email', $email, PDO::PARAM_STR);
                $setencia->bindParam(':nit', $nit, PDO::PARAM_STR);
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

    public static function insertarIPS($conexion, $IPS)
    {
        $insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO ips(id, nombre, nit, email, password, direccion, fecha_registro) VALUES(:id, :nombre, :nit, :email, :password, :direccion, NOW())";
                $setencia = $conexion->prepare($sql);

                $idtemp = $IPS->getId();
                $nombretemp = $IPS->getNombre();
                $nittemp = $IPS->getNit();
                $emailtemp = $IPS->getEmail();
                $passwordtemp = $IPS->getPassword();
                $direcciontemp = $IPS->getDireccion();

                $setencia->bindParam(':id', $idtemp, PDO::PARAM_STR);
                $setencia->bindParam(':nombre', $nombretemp, PDO::PARAM_STR);
                $setencia->bindParam(':nit', $nittemp, PDO::PARAM_STR);
                $setencia->bindParam(':email', $emailtemp, PDO::PARAM_STR);
                $setencia->bindParam(':password', $passwordtemp, PDO::PARAM_STR);
                $setencia->bindParam(':direccion', $direcciontemp, PDO::PARAM_STR);
                $insertado = $setencia->execute();
            } catch (PDOException $ex) {
                print "ERROR" . $ex->getMessage();
            }
        }
        return $insertado;
    }
}