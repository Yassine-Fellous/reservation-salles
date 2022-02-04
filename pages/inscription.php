<article>
<?php
@$login=$_POST['login'];
@$password=$_POST['password'];
@$passwordconfirm=$_POST['passwordconfirm'];
@$valider=$_POST['valider'];

$message="";
if(isset($valider)){
    if(empty($login)) $message="<li><div class= messagered> Vous devez entrer un login</div></li>";
    if(empty($password)) $message="<li><div class= messagered> Vous devez entrer un mot de passe</div></li>";
    if($password != $passwordconfirm) $message="<li><div class= messagered> Les mots de passe doivent être identiques</div></li>";
    if(empty($message)) {
        include("../classes/Db.php");
        $req=$pdo->prepare("SELECT ID FROM utilisateurs WHERE login=? limit 1");
        $req->setFetchMode(PDO::FETCH_ASSOC);
        $req->execute(array($login));
        $tab=$req->fetchAll();
        if(count($tab)>0)
            $message="<li><div class= messagered>Login existe déjâ !</div></li>";
        else{
            $ins=$pdo->prepare("INSERT into utilisateurs(login,password) VALUES (?,?)");
            $ins->execute(array($login, md5($password)));
            $message="<li><div class= messagegreen> Inscription réussie ! Vous allez être redirigé vers la page de connexion.</div></li>";
        header("Refresh:1.5; url=connexion.php");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href = "../style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
<header>
<?php
include('includes/header.html');
?>
</header>

<main>

<div class = registertitle>Inscription</div>
<div class = registerform>

<form name ="registerform" method = "POST" action = "#" enctype = "multipart/form-data">
<div class ="label"> Login </div>
    <input type = "text" name="login" value = '' placeholder = Login />           
    <div class = "label"> Mot de passe </div>
    <input type = "password" name ="password" value = "" />
    <div class = "label"> Confirmation mot de passe </div>
    <input type = "password" name ="passwordconfirm" value = "" />      <br>
    <div class =registerbutton> <input type = "submit" name = "valider" value = "S'inscrire" /> </div>
</form>
</div>

<?php if(!empty($message)){ ?>
<div id="message"><?php echo $message ?></div>
<?php } ?>
<div class = alreadyregister>
<p>Déjâ inscrit ? <br> <br><a href ="connexion.php"> Se connecter à un compte existant</a> </p>
</div>

<footer>
<?php
include('includes/footer.html');
?>
</footer>
</body>
</html>

</article>