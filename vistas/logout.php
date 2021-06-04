<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
ControlSesion::cerrarSesion();
header('Location: ' . RUTA_LOGIN, true, 301);
exit();