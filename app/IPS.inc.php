<?php


class IPS
{
    private $id;
    private $nombre;
    private $nit;
    private $email;
    private $password;
    private $direccion;
    private $fecha_registro;

    public function __construct($id, $nombre, $nit, $email, $password, $direccion, $fecha_registro)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nit = $nit;
        $this->email = $email;
        $this->password = $password;
        $this->direccion = $direccion;
        $this->fecha_registro = $fecha_registro;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNit()
    {
        return $this->nit;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }

}