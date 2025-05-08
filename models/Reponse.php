<?php
class Reponse {
    private $id;
    private $id_reclamation;
    private $message;
    private $date_reponse;
    private $id_admin;
    
    public function __construct($id = null, $id_reclamation = null, $message = null, $date_reponse = null, $id_admin = null) {
        $this->id = $id;
        $this->id_reclamation = $id_reclamation;
        $this->message = $message;
        $this->date_reponse = $date_reponse;
        $this->id_admin = $id_admin;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getIdReclamation() {
        return $this->id_reclamation;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function getDateReponse() {
        return $this->date_reponse;
    }
    
    public function getIdAdmin() {
        return $this->id_admin;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setIdReclamation($id_reclamation) {
        $this->id_reclamation = $id_reclamation;
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setDateReponse($date_reponse) {
        $this->date_reponse = $date_reponse;
    }
    
    public function setIdAdmin($id_admin) {
        $this->id_admin = $id_admin;
    }
}
?>
