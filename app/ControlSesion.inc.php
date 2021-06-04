<?php

class ControlSesion
{

    public static function iniciarSesion($id_usuario, $nombre_usuario, $estado_usuario)
    {
        if (session_id() == '') {
            session_start();
        }

        $_SESSION['id'] = $id_usuario;
        $_SESSION['nombre'] = $nombre_usuario;
        $_SESSION['estado'] = $estado_usuario;
    }

    public static function cerrarSesion()
    {
        if (session_id() == '') {
            session_start();
        }

        if (isset($_SESSION['id'])) {
            unset($_SESSION['id']);
        }

        if (isset($_SESSION['nombre'])) {
            unset($_SESSION['nombre']);
        }

        if (isset($_SESSION['estado'])) {
            unset($_SESSION['estado']);
        }

        session_destroy();
    }

    public static function sesionIniciada()
    {
        if (session_id() == '') {
            session_start();
        }

        if (isset($_SESSION['id']) && isset($_SESSION['nombre']) && isset($_SESSION['estado'])) {
            return true;
        } else {
            return false;
        }
    }
}

?>