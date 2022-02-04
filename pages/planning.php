<?php 
require_once('../classes/classCrenneau.php');
require_once('../classes/classWeek.php');
require_once('../classes/User.php');

$path_index="../index.php";
$path_inscription="inscription.php";
$path_connexion="connexion.php";
$path_profil="profil.php";
$path_planning="planning.php";
$path_booking="reservation.php";
$path_BookingForm="reservation-form.php";

header('Content-type: text/html; charset=UTF-8');
?>

<?php
 session_start();

if(!isset($_SESSION['login'])){
include('includes/header.html');
}
if (isset($_SESSION['login'])) {
include('../pages/includes/loggedbar.php');
}
?>

<?php 

 date_default_timezone_set('Europe/Paris'); 

$eventsFromDB = new Creneaux(); 
$tableCell = [];
$currentEvent = []; 

$actWeek = new Week($_GET['day'] ?? null, $_GET['month'] ?? null, $_GET['year'] ?? null);

$tempDate = new DateTime ($actWeek->currentDate);
$end =(clone $tempDate)->modify('+ 5 days - 1 second'); 

$events = $eventsFromDB -> getEventsBetweenByDayTime($tempDate, $end); 

foreach ($events as $k => $event){
    $tableCell[$event['case']] = $event['length']; 
}


?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning</title>
</head>

    
<html lang="fr">
<link rel="stylesheet" href="../CSS/planning.css">
<body>

    <main>
    <div class="calendarnav">
    
        <a href="planning.php?day=<?= $actWeek->previousWeek()->day;?>&month=<?= $actWeek->previousWeek()->month; ?> &year=<?= $actWeek->previousWeek()->year; ?>"></a>

        <h1>Planning: Semaine actuelle, Février <?= $actWeek->ToString(); ?></h1>

        <a href="planning.php?day=<?= $actWeek->nextWeek()->day;?>&month=<?= $actWeek->nextWeek()->month; ?> &year=<?= $actWeek->nextWeek()->year; ?>">  </a>
    </div>
    <div class = calendartable>     
    <table>
    </div>
    
    <colgroup>
        <col>
        <col span="5">
        <col span="2">
    </colgroup>

    <?php 
    // GENERATION DU TABLEAU 
    //BOUCLE POUR LIGNES // 11H

    for ($y=0; $y < 12; $y++) { 
        echo '<tr>', "\n";

        // BOUCLE POUR COLONNES (jours)
        for ($x = 0; $x < 8; $x++) { 
            //coordinate = équation des 2 boucles ()
            $coordinate = $y . '-' . $x;
            $cellLength = null; 

            // ON SET LA CELLULE HORAIRES
            if ($y == 0 && $x == 0)
            echo '<th>Horaires</th>';
            // ON SET LES JOURS
            // si y==0 les heures sont réinitialisés et on passe au jour suivant 
            elseif ($y == 0 && $x > 0) {
                
                $daysNumber = $actWeek->mondayDate + $x - 1;
                if ($daysNumber > 31){

                        $daysNumber = $actWeek->mondayDate + $x - 32;
                    
                }
                 echo '<th>' . $actWeek->getDays ($x -1) . ' ' . $daysNumber . '</th>';
            }


            // ON SET LES HEURES 
            //si x==0 on passe à une autre semaine
            elseif ($y > 0 && $x == 0) {
                $tempHour = 7 + $y; 
                if ($tempHour < 10) {
                   $hour = '0' . $tempHour . ':00'; // hypothèse : réservation max de 9h
                }
                else {
                    $hour = $tempHour . ':00'; 
                }
                echo '<th>' . $hour . '</th>';
            }

        
            else {
                foreach($tableCell as $key => $value) {
                    if ($coordinate === $key) {
                        $cellLength = $value;
                    }
                } 
                
                // foreach ($tableCell as $key => $value) {
                //     if ($coordinate === $key) {
                //         $cellLength = $value; 
                //     }
                // }
                
                foreach ($events as $k => $event) {

                    if ($coordinate == $event['case']) {
                        $currentEvent = $event;
                    }
                
                } 
                if (isset($cellLength) && $cellLength !== FALSE) {
                    //fusion des cellules en fonction du temps de l'event 
                    echo '<td rowspan="'. $cellLength .'"';
                    echo ' style="color:white;text-shadow: 1px 1px 1px black; background-color:' . '">';
                    echo "<a href=\"reservation.php?id=" . $currentEvent['id'] . '" class=table_link>';
                    echo '<div class = loginreserv><span>' . $currentEvent['login'] . '</span></div>'; 
                    echo '<div class = titlereserv><span>' . $currentEvent['titre'] . '</span></div>'; 
                    echo '</a>'; 
                    echo '</td>';
                



                    //tant que la reservation fait plus d'une heure on dit que la case d'en dessous = FALSE
                    $tempY = $y + 1; 
                    while ($cellLength > 1) {
                       $tableCell[$tempY . '-' . $x] = FALSE; 
                       // on se prépare à checker la case d'en dessous
                       $tempY++; 
                       $cellLength--;
                    }
                }     
                else {
                    if (isset($tableCell[$coordinate])){
                        ;
                }
                else {
                    echo'<td></td>'; 
                }
            }
        }
    }

    
    echo '</tr> ', "\n"; 
    }
    
    ?>

    </table>

<div class = booking>

    <div class = addbooking><a href="reservation-form.php"> Faire une  demande de réservation </a></div>
</div>


<?php

$test = new Creneaux();
$test->getEventById(1);


?>
    </main>


<footer>
    <?php

     require_once('includes/footer.html'); 
    ?>
</footer>

</body>
</html>

    <link rel="stylesheet" href="CSS/planning.css">
    <link rel="stylesheet" href="/reservation-salles/style.css">

<?php
$path_img_footer1 = '../images/logobbYellow.png';
$path_img_footer2 ='../images/logotomate.PNG';
$path_footer='CSS/footer.css';
?>