<?php

class Reclamation {
    private ?int $id;
    private ?string $type_utilisateur;
    private ?int $id_utilisateur;
    private ?string $sujet;
    private ?string $message;
    private ?DateTime $date_reclamation;

    public function __construct(?int $id, ?string $type_utilisateur, ?int $id_utilisateur, ?string $sujet, ?string $message, ?DateTime $date_reclamation) {
        $this->id = $id;
        $this->type_utilisateur = $type_utilisateur;
        $this->id_utilisateur = $id_utilisateur;
        $this->sujet = $sujet;
        $this->message = $message;
        $this->date_reclamation = $date_reclamation;
    }

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getTypeUtilisateur(): ?string { return $this->type_utilisateur; }
    public function setTypeUtilisateur(?string $type_utilisateur): void { $this->type_utilisateur = $type_utilisateur; }

    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }

    public function getSujet(): ?string { return $this->sujet; }
    public function setSujet(?string $sujet): void { $this->sujet = $sujet; }

    public function getMessage(): ?string { return $this->message; }
    public function setMessage(?string $message): void { $this->message = $message; }

    public function getDateReclamation(): ?DateTime { return $this->date_reclamation; }
    public function setDateReclamation(?DateTime $date_reclamation): void { $this->date_reclamation = $date_reclamation; }
}
?>