<html>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

    

<div class="topnav">

  <a href="#home" class="active"> <?php  if (isset($_SESSION['login'])) {
      echo 'Connecté en tant que '.$_SESSION['login']; } ?> </a>

  <div id="menuprofil">
  <a href = "/reservation-salles/pages/planning.php"> Planning des réservations </a>
    <a href="/reservation-salles/pages/profil.php">Modifier mon profil</a>
    <a  href = "/reservation-salles/pages/logout.php" >Déconnexion</a>
  </div>


  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>
<script>
function myFunction() {
  var x = document.getElementById("menuprofil");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
</script>
</html>