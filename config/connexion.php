<?php
class config
{
    private static $pdo=null;

    public static function getConnexion()
    {
        if(!isset(self::$pdo)){
            $servername="localhost";
            $username="root";
            $password="";
            $dbname="covoiturage";
            try{
                self::$pdo=new PDO("mysql:host=$servername;dbname=$dbname",$username,$password,
                [
                    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
                );
<<<<<<< HEAD
                echo "connected  successfully";
=======
                /*echo "connected  successfully";*/
>>>>>>> bfbf316 (second commit)
            }catch(Exception $e){
                die('Erreur: '.$e->getMessage());
            }
        }
        return self::$pdo;

    }
}
config::getConnexion();
?>
