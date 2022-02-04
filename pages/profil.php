<?php
session_start();

@$login=$_POST["login"];
@$password= $_POST["password"];
@$passwordconfirm=$_POST['passwordconfirm'];
@$changepassword=$_POST['changepassword'];
@$changelogin=$_POST['changelogin'];
@$id = $_SESSION['id'];

if($_SESSION ["autoriser"]!="oui") {
    header("location:/reservation-salles/pages/connexion.php");
    exit();
}

include("../classes/Db.php");

if (isset($_GET['logout'])) {

    header("location: /reservation-salles/pages/logout.php");
}

if(isset($changelogin)){


    if (empty($login)) {
        echo ("<div class = messagered> Le champs ne peut pas être vide </div>");
    }

    else {

        $req=$pdo->prepare("SELECT ID FROM utilisateurs WHERE login=? limit 1");
        $req->setFetchMode(PDO::FETCH_ASSOC);
        $req->execute(array($login));
        $tab=$req->fetchAll();
        if(count($tab) > 0){
            echo("<div class= messagered> Ce nom d'utilisateur est déjâ utilisé </div>");
    }
    else{
            $_SESSION['login'] = $login;
            $ins=$pdo->prepare("UPDATE utilisateurs SET login='$login' WHERE id='$id'");
            $ins->execute(array($login));
            echo ("<div class= messagegreen> modification réussie ! La page va s'actualiser.</div>");
            header("Refresh:2; url=/reservation-salles/pages/profil.php");

        }
    }
}

if(isset($changepassword)){



    if($password!=$passwordconfirm) {
    echo ("<div class = messagered> Les mot de passes ne sont pas identiques ! </div>");
    }
    else if (empty($password) || empty($passwordconfirm)) {
        echo ("<div class = messagered> Un ou plusieurs champs sont vides. Veillez les remplir. </div>");
    }

    else {

        $req=$pdo->prepare("SELECT ID FROM utilisateurs WHERE login=? limit 1");
        $req->setFetchMode(PDO::FETCH_ASSOC);
        $req->execute(array($login));
        $tab=$req->fetchAll();
        if(count($tab) > 0){
            echo("<div class= messagered>Login existe déjâ !</div>");
    }

    else{
            // $_SESSION['login'] = $login;
            @$password= md5($_POST["password"]);
            $ins=$pdo->prepare("UPDATE utilisateurs SET password='$password' WHERE id='$id'");
            $ins->execute(array(md5($password)));
            echo ("<div class= messagegreen> modification réussie ! Vous allez être redirigé vers la page de connexion.</div>");
            header("Refresh:2; url=/reservation-salles/pages/profil.php");

        }
    }
}
    
?>

<?php

if(isset($_SESSION['login'])){
    include ('includes/loggedbar.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href = "../style.css" />
    <title>Profil</title>
</head>
<body>
<main>


<div class = logintitle>
<span>
    Bonjour 


<?php echo ($_SESSION["login"]); ?>


<br>
Modification des identifiants de votre profil : 
</span>  
</div>
<div class = modifyform>
<div class = modifylogin>
<form method="POST" action = "">
<div class ="label"> Modifier le login </div>
<input type = "text" name = "login" value = ""  placeholder = <?php echo ($_SESSION["login"]); ?>  /> 
<div class = modifyloginbutton><input type = "submit" name = "changelogin" value = "Modifier" /></div> <br>
</form></div>
</div>

<div class = modifypassword>
<form method = "POST" action = "">
<div class ="label"> Entrer le nouveau mot de passe </div>
<input type = "password" name = "password" />
<div class ="label"> Confirmer le nouveau mot de passe </div>
<input type = "password" name = "passwordconfirm" /> 
<div class = modifypasswordbutton><input type = "submit" name = "changepassword" value = "Modifier" /> </div>
</form></div>
</main>

<footer>
<?php
include('includes/footer.html');
?>
</footer>

</body>
</html>