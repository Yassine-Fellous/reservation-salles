<?php 

 session_start();
// CHEMINS
$path_index="../index.php";
$path_inscription="inscription.php";
$path_connexion="connexion.php";
$path_profil="profil.php";
$path_planning="planning.php";
$path_booking="";
$path_BookingForm="reservation-form.php";
// STYLESHEET HEADER 
require_once('includes/header.php');
// REQUIRE CONFIG 
require_once('../classes/fonctions.php');
require_once('../classes/classCrenneau.php');
require_once('../classes/classWeek.php');
require_once('../classes/User.php');
require_once('../classes/Db.php');

if (isset($_SESSION['id'])) {
    // RECUP INFO FROM DB
    $event = new Creneaux;
    $eventInfos = $event->getEventbyId($_GET["id"]);

    // FORMAT DATE & TIME
    $timestampStart = strtotime($eventInfos['debut']);
    $timestampEnd = strtotime($eventInfos['fin']);
    $formated = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::MEDIUM);
}

else {
    $_SESSION['error'] = "<div class = messagered>Cette page n'a pas été accédé par le planning</div>";
}


if (!isset($_SESSION['id'])) {
header("location:/reservation-salles/index.php");
}
?>
<!-- CSS -->
<link rel="stylesheet" href="../CSS/reservation.css">
<link rel="stylesheet" href="../style.css">

<main>


<h1> Réservation n° <?php if (isset($_SESSION['id'])) { echo($_GET["id"]);  }?>  </h1>

<?php


    if (isset($_SESSION['error'])):
        echo '<p class="error">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    else :
?>
  
    <section id="booking">

    <article class="Pbooking">
    <p>Réservation réalisée par:</p> <?= $eventInfos['login']; ?>
    </article>

    <article class="Pbooking">
    <p>titre:</p> <?= $eventInfos['titre']; ?>
    </article>

    <article class="Pbooking">
    <p>description:</p><br>
        <?= $eventInfos['description']; ?>
    </article>

    <article class="Pbooking">
    <p>Commence le </p> <?= $formated->format($timestampStart) ?>, <br>
    <p> et finit le </p> <?= $formated->format($timestampEnd); ?>.
    </article>

    </section>
    <?php endif; ?>
</main>


<?php


 require_once('includes/footer.html'); 
?>