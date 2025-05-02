<?php

class Reservation {
    private ?int $IDR = null;       // ID de la réservation
    private ?int $IDT = null;       // ID du trajet
    private ?int $v_pass = null;    // Nombre de places réservées
    private ?int $v_pv = null;      // Petites valises
    private ?int $v_mv = null;      // Moyennes valises
    private ?int $v_gv = null;      // Grandes valises
    private ?float $prix = null;    // Prix total

    // Constructeur
    public function __construct(
        ?int $IDR = null,
        ?int $IDT = null,
        ?int $v_pass = null,
        ?int $v_pv = null,
        ?int $v_mv = null,
        ?int $v_gv = null,
        ?float $prix = null
    ) {
        $this->IDR = $IDR;
        $this->IDT = $IDT;
        $this->v_pass = $v_pass;
        $this->v_pv = $v_pv;
        $this->v_mv = $v_mv;
        $this->v_gv = $v_gv;
        $this->prix = $prix;
    }

    // SETTERS
    public function setIDR($IDR) { $this->IDR = $IDR; return $this; }
    public function setIDT($IDT) { $this->IDT = $IDT; return $this; }
    public function setV_PASS($v_pass) { $this->v_pass = $v_pass; return $this; }
    public function setV_PV($v_pv) { $this->v_pv = $v_pv; return $this; }
    public function setV_MV($v_mv) { $this->v_mv = $v_mv; return $this; }
    public function setV_GV($v_gv) { $this->v_gv = $v_gv; return $this; }
    public function setPRIX($prix) { $this->prix = $prix; return $this; }

    // GETTERS
    public function getIDR() { return $this->IDR; }
    public function getIDT() { return $this->IDT; }
    public function getV_PASS() { return $this->v_pass; }
    public function getV_PV() { return $this->v_pv; }
    public function getV_MV() { return $this->v_mv; }
    public function getV_GV() { return $this->v_gv; }
    public function getPRIX() { return $this->prix; }

    private ?string $passenger_email = null;

    // Ajoutez les getter/setter correspondants
    public function setPassengerEmail($email) { $this->passenger_email = $email; return $this; }
    public function getPassengerEmail() { return $this->passenger_email; }
}
?>
