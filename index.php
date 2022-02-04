
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>

<body>
<main>
<header>

<?php
session_start();

if(!isset($_SESSION['login'])){
include('pages/includes/header.html');
}

if (isset($_SESSION['login'])) {
include('pages/includes/loggedbar.php');
}

?>
</header>


<div class = indexbanner>
<div class = index1>
<h1> Réservez votre salle de co-working ! <h1>
</div>
<br>
<br>
<br>
<br>
<br>
<div class = index2>
<a href="pages/inscription.php"> &nbsp &nbsp  Créer un compte </a>
</div>
</div>

</main>
<footer>
<?php
include('pages/includes/footer.html');
?>
</footer>

</body>
</html>