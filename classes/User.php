<?php
class User_connect
{
    private $id;
    public $login;
    public $password;



    public function connectUser($login, $password)
    { 
        $bdd = new Bdd(); 
        $pdo = $bdd->connectDb(); 

        $q = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $q->bindparam(':login', $login, PDO::PARAM_STR);
        $q->execute();
        $user = $q->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            if (password_verify($password, $user['password'])) {
                $this->id = $user['id'];
                $this->login = $user['login'];
                $this->password = $user['password'];
  
                $_SESSION['utilisateur'] = [
                    'id' =>
                        $this->id,
                    'login' =>
                        $this->login,
                    'password' =>
                        $this->password
             
                ];
                
                header('location:/reservation-salles/pages/profil.php');
                return $_SESSION['utilisateur'];
            } else {
                echo "Le login ou le mot de passe est erroné.";
            }
        } else {
            echo "Le login ou le mot de passe est erroné.";
        }
    }

    public function Disconnect(){

        session_unset();
        session_destroy();
        
    }
}
/* /* // function BDD(){
//     $bdd = NEW PDO('mysql:dbname=reservationsalles;host=127.0.0.1', 'root','');
//     //if(!isset($_SESSION))
//     //{
//         //session_start();
//     //}
//     return $bdd;
// } */
 


?>