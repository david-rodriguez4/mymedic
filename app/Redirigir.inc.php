<?php

class Redirigir {

    public static function redireccion($url) {
        header('Location: ' . $url, true, 301);
        exit();
    }

}