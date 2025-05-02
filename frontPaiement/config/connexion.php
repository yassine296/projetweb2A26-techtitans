<?php
class Database {
    private static $instance = null;
    
    public static function getConnection() {
        if (!self::$instance) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=hezni',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
?>