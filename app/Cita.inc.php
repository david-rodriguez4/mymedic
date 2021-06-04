<?php


class Cita
{
    private $id;
    private $id_paciente;
    private $id_doctor;
    private $valoracion;
    private $fecha_reserva;
    private $fecha_atencion;
    private $estado;

    public function __construct($id, $id_paciente, $id_doctor, $valoracion, $fecha_reserva, $fecha_atencion, $estado)
    {
        $this->id = $id;
        $this->id_paciente = $id_paciente;
        $this->id_doctor = $id_doctor;
        $this->valoracion = $valoracion;
        $this->fecha_reserva = $fecha_reserva;
        $this->fecha_atencion = $fecha_atencion;
        $this->estado = $estado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdPaciente()
    {
        return $this->id_paciente;
    }

    public function getIdDoctor()
    {
        return $this->id_doctor;
    }

    public function getValoracion()
    {
        return $this->valoracion;
    }

    public function getFechaReserva()
    {
        return $this->fecha_reserva;
    }

    public function getFechaAtencion()
    {
        return $this->fecha_atencion;
    }

    public function getEstado()
    {
        return $this->estado;
    }

}