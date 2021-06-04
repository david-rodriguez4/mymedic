<?php
include_once 'app/RepositorioCita.inc.php';
include_once 'app/RepositorioPaciente.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';

$componentes_url = parse_url($_SERVER['REQUEST_URI']);

$ruta = $componentes_url['path'];
$partes_ruta = explode('/', $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);

$ruta_elegida = "vistas/404.php";

if ($partes_ruta[0] == 'mymedic') {
    if (count($partes_ruta) == 1) {
        $ruta_elegida = "vistas/home.php";
    } elseif (count($partes_ruta) == 2) {
        switch ($partes_ruta[1]) {
            case 'logout';
                $ruta_elegida = 'vistas/logout.php';
                break;
            case 'registro';
                $ruta_elegida = 'vistas/registro.php';
                break;
            case 'registro-correcto';
                $ruta_elegida = 'vistas/registro-correcto.php';
                break;
            case 'login';
                $ruta_elegida = 'vistas/login.php';
                break;
            case 'resumen';
                $ruta_elegida = 'vistas/resumen.php';
                break;
            case 'citas';
                $ruta_elegida = 'vistas/citas.php';
                break;
            case 'doctores';
                $ruta_elegida = 'vistas/doctores.php';
                break;
            case 'usuarios';
                $ruta_elegida = 'vistas/pacientes.php';
                break;
        }
    } elseif (count($partes_ruta) == 3) {
        if ($partes_ruta[1] == 'cita') {
            $url = $partes_ruta[2];
            Conexion::abrirConexion();
            $cita = RepositorioCita::obtenerCitaIdAtender(Conexion::obtenerConexion(), $url);
            if ($cita != null) {
                $paciente = RepositorioPaciente::obtenerPacienteId(Conexion::obtenerConexion(), $cita->getIdPaciente());
                $ruta_elegida = 'vistas/cita.php';
            }
            Conexion::cerrarConexion();
        }
    }
}

include_once $ruta_elegida;