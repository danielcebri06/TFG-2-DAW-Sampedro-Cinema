<?php

namespace App\Modelo\Entidades;

class Pago {
    private ?int $id_pago;
    private int $id_reserva;
    private float $importe;
    private string $estado;
    private string $fecha_hora;
    private string $stripe_payment_intent_id;

    public function __construct(
        ?int $id_pago,
        int $id_reserva,
        float $importe,
        string $estado,
        string $fecha_hora,
        string $stripe_payment_intent_id
    ) {
        $this->id_pago = $id_pago;
        $this->id_reserva = $id_reserva;
        $this->importe = $importe;
        $this->estado = $estado;
        $this->fecha_hora = $fecha_hora;
        $this->stripe_payment_intent_id = $stripe_payment_intent_id;
    }

    public function getId_pago(): ?int {
        return $this->id_pago;
    }

    public function getId_reserva(): int {
        return $this->id_reserva;
    }

    public function getImporte(): float {
        return $this->importe;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getFecha_hora(): string {
        return $this->fecha_hora;
    }

    public function getStripe_payment_intent_id(): string {
        return $this->stripe_payment_intent_id;
    }

    public function getStripe_payment_intent(): string {
        return $this->stripe_payment_intent_id;
    }
}

