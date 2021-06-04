<?php
include_once 'app/RepositorioDoctor.inc.php';
include_once 'app/Doctor.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';

if (!ControlSesion::sesionIniciada() || $_SESSION['estado'] != 'IPS') {
    header('Location: ' . SERVIDOR, true, 301);
    exit();
}

$citas = 0;
$doctores = 0;
$pacientes = 0;
Conexion::abrirConexion();
$conexion = Conexion::obtenerConexion();
if (isset($conexion)) {
    try {
        $sql = "SELECT * FROM citas WHERE DAY(fecha_atencion) LIKE DAY(NOW()) AND MONTH(fecha_atencion) LIKE MONTH(NOW()) AND YEAR(fecha_atencion) LIKE YEAR(NOW())";
        $setencia = $conexion->prepare($sql);
        $setencia->execute();
        $resultado = $setencia->fetchAll();
        $citas = count($resultado);
    } catch (PDOException $ex) {
        print "ERROR" . $ex->getMessage();
    }
}
if (isset($conexion)) {
    try {
        $sql = "SELECT * FROM citas";
        $setencia = $conexion->prepare($sql);
        $setencia->execute();
        $resultado = $setencia->fetchAll();
        $doctores = count($resultado);
    } catch (PDOException $ex) {
        print "ERROR" . $ex->getMessage();
    }
}
if (isset($conexion)) {
    try {
        $sql = "SELECT * FROM pacientes WHERE id_ips = :id_ips";
        $setencia = $conexion->prepare($sql);
        $setencia->bindParam(':id_ips', $_SESSION['id'], PDO::PARAM_STR);
        $setencia->execute();
        $resultado = $setencia->fetchAll();
        $pacientes = count($resultado);
    } catch (PDOException $ex) {
        print "ERROR" . $ex->getMessage();
    }
}

include_once 'platillas/doc-declaracion.inc.php';
include_once 'platillas/navbar.inc.php';
?>
<br>
<div class="uk-container uk-align-center">
    <div class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3>TOTAL CITAS HOY <i class="fa fa-calendar" aria-hidden="true"></i></h3>
                <p><?php echo $citas ?></p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3>TOTAL CITAS <i class="fa fa-calendar-check-o" aria-hidden="true"></i></h3>
                <p><?php echo $doctores ?></p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3>TOTAL PACIENTES <i class="fa fa-users" aria-hidden="true"></i></h3>
                <p><?php echo $pacientes ?></p>
            </div>
        </div>
    </div>
</div>
<?php
include_once 'platillas/doc-cierre.inc.php';
?>
