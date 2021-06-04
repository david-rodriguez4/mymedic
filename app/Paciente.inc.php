<?php


class Paciente
{
    private $id;
    private $nombre;
    private $documento;
    private $telefono;
    private $email;
    private $password;
    private $direccion;
    private $fecha_registro;
    private $id_ips;
    private $estado;

    public function __construct($id, $nombre, $documento, $telefono, $email, $password, $direccion, $fecha_registro, $id_ips, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->documento = $documento;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->password = $password;
        $this->direccion = $direccion;
        $this->fecha_registro = $fecha_registro;
        $this->id_ips = $id_ips;
        $this->estado = $estado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function getTelefono()
    {
        return $this->telefono;
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

    public function getIdIps()
    {
        return $this->id_ips;
    }

    public function getEstado()
    {
        return $this->estado;
    }

}