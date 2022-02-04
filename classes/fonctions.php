<?php
//session_start();
require_once('Db.php');
require('User.php');

class User{
    public $bdd;
    private $id;
    public $login;

    public function __construct(){
    }

    public function setUser($user){
        $this->id =$_SESSION['id'];
    }
    

    // fonction pour crypter mes champs
    public function secure($var){
        $var = htmlspecialchars(trim($var)); 
        return $var;  
    }

//--------------------------------------- CONNEXION -------------------------------------------------------//

    public function connect(){
        $db  = new Bdd();
        $bdd = NEW PDO('mysql:dbname=reservationsalles;host=127.0.0.1', 'root','');

        if(isset($_POST['connect'])){
            $login = $_POST['login'];
            $password = $_POST['password'];
           
            
            if (!empty($login) && (!empty($password))) {
                $GetAllInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = :login");
                $GetAllInfo->bindValue(':login', $login);
                $GetAllInfo->execute(); 
            
                $AllUserInfo = $GetAllInfo->fetch(PDO::FETCH_ASSOC);

                    if (!empty($AllUserInfo)) {
                        
                        if (password_verify($password, $AllUserInfo['password'])) {
                            $_SESSION['connected'] == true; 
                            $_SESSION['utilisateur'] = $AllUserInfo['login']; 
                            $_SESSION['id'] = $AllUserInfo['id'];
                            header('Location:profil.php');
                        } else {
                            echo "Mot de passe incorrect";
                        }
                    } 
                        else {
                            echo"Identifiant inconnu";
                    }
            } 
                        else {
                            echo"Identifiant incorrect";
            }
        } 

    }
        

public function register($login,$password,$confirmPW){
    

    if (isset($_POST["register"])){   

        if (strlen($login) <=5){  
        $_SESSION['error']="Veuillez insérer un minimum de 5 caractères dans chaque champ";
        return $_SESSION['error']; 
        }

        elseif (strlen($password) <=5) {
        $_SESSION['error']="Veuillez insérer un minimum de 5 caractères dans chaque champ"; 
        return $_SESSION['error'];   
        }

        elseif (strlen($confirmPW) <=5) {
        $_SESSION['error']="Veuillez insérer un minimum de 5 caractères dans chaque champ"; 
        return $_SESSION['error'];        
        }

        else{

            if($password == $confirmPW){

                echo"coucou4"; 
                $bdd = new Bdd(); 
                $pdo = $bdd->connectDb();
                $checklogin = $pdo->prepare("SELECT login FROM utilisateurs WHERE login = :login");
                $checklogin->bindValue(':login', $login);
                $checklogin->execute(); 
                $count= $checklogin->fetch();
                $password = password_hash($password, PASSWORD_DEFAULT);
    
                if (!$count) { 
                    echo"coucou5";
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (login,password) VALUE (?,?)");
                    $stmt->bindValue(1,$login);
                    $stmt->bindValue(2,$password);
                    $stmt->execute();
                    $id = $pdo->lastInsertId();
                    $this->id = $id;
                    $this->login = $login;
                    $this->password = $password;
                    header('Location:connexion.php');
                    return array($login,$password);
                }           
        }

        }

        

    }
}  
    }   
?>