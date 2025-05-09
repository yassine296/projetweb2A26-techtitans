<?php

class Trajets {
    private ?int $IDT = null;
    private ?string $V_DEP = null;
    private ?string $V_ARR = null;
    private ?string $DATE = null;
    private ?string $HEURE = null;
    private ?int $NB_PASS = null;
    private ?int $NB_PV = null;
    private ?int $NB_MV = null;
    private ?int $NB_GV = null;
    private ?float $PRIX = null;
    private ?int $id_conducteur = null; // Nouvel attribut

    // Constructeur
    public function __construct(
        ?int $IDT = null, ?string $V_DEP = null, ?string $V_ARR = null,
        ?string $DATE = null, ?string $HEURE = null, ?int $NB_PASS = null,
        ?int $NB_PV = null, ?int $NB_MV = null, ?int $NB_GV = null, ?float $PRIX = null,
        ?int $id_conducteur = null // Ajout dans le constructeur
    ) {
        $this->IDT = $IDT;
        $this->V_DEP = $V_DEP;
        $this->V_ARR = $V_ARR;
        $this->DATE = $DATE;
        $this->HEURE = $HEURE;
        $this->NB_PASS = $NB_PASS;
        $this->NB_PV = $NB_PV;
        $this->NB_MV = $NB_MV;
        $this->NB_GV = $NB_GV;
        $this->PRIX = $PRIX;
        $this->id_conducteur = $id_conducteur;
    }

    // SETTERS
    public function setIDT($IDT) { $this->IDT = $IDT; return $this; }
    public function setV_DEP($V_DEP) { $this->V_DEP = $V_DEP; return $this; }
    public function setV_ARR($V_ARR) { $this->V_ARR = $V_ARR; return $this; }
    public function setDATE($DATE) { $this->DATE = $DATE; return $this; }
    public function setHEURE($HEURE) { $this->HEURE = $HEURE; return $this; }
    public function setNB_PASS($NB_PASS) { $this->NB_PASS = $NB_PASS; return $this; }
    public function setNB_PV($NB_PV) { $this->NB_PV = $NB_PV; return $this; }
    public function setNB_MV($NB_MV) { $this->NB_MV = $NB_MV; return $this; }
    public function setNB_GV($NB_GV) { $this->NB_GV = $NB_GV; return $this; }
    public function setPRIX($PRIX) { $this->PRIX = $PRIX; return $this; }
    public function setIdConducteur($id_conducteur) { $this->id_conducteur = $id_conducteur; return $this; }

    // GETTERS
    public function getIDT() { return $this->IDT; }
    public function getV_DEP() { return $this->V_DEP; }
    public function getV_ARR() { return $this->V_ARR; }
    public function getDATE() { return $this->DATE; }
    public function getHEURE() { return $this->HEURE; }
    public function getNB_PASS() { return $this->NB_PASS; }
    public function getNB_PV() { return $this->NB_PV; }
    public function getNB_MV() { return $this->NB_MV; }
    public function getNB_GV() { return $this->NB_GV; }
    public function getPRIX() { return $this->PRIX; }
    public function getIdConducteur() { return $this->id_conducteur; }

    public function getDriverEmail() {
        // À adapter selon la logique métier
        return "zariatyassine1@gmail.com";
    }
}
?>
