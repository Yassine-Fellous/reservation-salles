<?php
session_start();
    // CHEMINS
    $path_index="../index.php";
    $path_inscription="inscription.php";
    $path_connexion="";
    $path_profil="profil.php";
    $path_planning="planning.php";
    $path_booking="reservation.php";
    $path_BookingForm="reservation-form.php";
    // HEADER
    

if(isset($_SESSION['login'])){
    include ('includes/loggedbar.php');
    }

    if(!isset($_SESSION['login'])){
    require_once('includes/header.html');
    }


    require_once('../classes/DB.php');
    require_once('../classes/fonctions.php');
    require_once('../classes/classCrenneau.php');
    $db = new PDO ('mysql:host=localhost;dbname=reservationsalles','root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
    $title = 'Formulaire de réservation';

    if(isset($_POST['cancel'])){
        header('Location: logout.php');
        return;
    }
    
    if(isset($_POST['submit'])){

        if(empty($_POST['title'])){ echo 'coucou1';
            $_SESSION['error'] = "Votre réservation n'est pas conforme";
            header('Location:reservation-salle/pages/reservation-form.php');
            return;
        }
        if (strlen($_POST['title']) > 20){ 
            $message="<div class = messagered> Le titre de la réservation ne peut pas dépasser 20 caractères !<br> Vous allez être rediriger vers le formulaire</div>";
            echo ($message);
            header('refresh: 5; URL=reservation-form.php');
            
        }
        elseif (empty($_POST['startTime'])) { echo 'coucou3';
            echo 'Vous devez choisir une heure de début pour votre réservation.';
            header('Location: reservation-form.php');
            return;
        }
        elseif (empty($_POST['endTime'])) { echo 'coucou4';
            $_SESSION['error'] = 'Vous devez choisir une heure de fin pour votre réservation..';
            header('Location: reservation-form.php');
            return;
        }
        elseif (empty($_POST['description'])) { echo 'coucou5';
            $_SESSION['error'] = 'Vous devez écrire une description pour votre réservation.';
            header('Location: reservation-form.php');
            return;
        }
        elseif (strlen($_POST['description']) > 7777) { echo 'coucou6';
            $_SESSION['error'] = 'Votre description est trop longue.';
            header('Location: reservation-form.php');
            return;
        }
     
        
        else{
            $dateArray = explode('-', $_POST['date']);
            
            $startTimeArray = explode(':', $_POST['startTime']);
            $endTimeArray = explode(':', $_POST['endTime']);

            $dateFormatted = implode('/', $dateArray);

            $timestamp = strtotime($_POST['date']);
            $dayOfWeek = date('N', $timestamp);

            $timestampNow = time();
            $dateTime = $_POST['date'] . ' ' ; $_POST['startTime'] . ':00';
            $resDateTime = strtotime($dateTime);

            if($dayOfWeek == 6 || $dayOfWeek == 7){
                $_SESSION['error'] = 'Vous ne pouvez pas faire de réservation durant les week-ends.';
                header('Location: reservation-form.php');
                return;
            }
            //LA
            elseif (!checkdate($dateArray[1], $dateArray[2], $dateArray[0] )){
                $_SESSION['error'] = 'Il y  erreur dans le formatage de votre jour de réservation.';
                header('Location: reservation-form.php');
                return;
            }
            elseif ($endTimeArray[0] <= $startTimeArray[0]) {
                $_SESSION['error'] = 'Il y  erreur dans le formatage de votre jour de réservation.';
                header('Location: reservation-form.php');
                return;
            }
            elseif (intval($startTimeArray[0]) < 8 || intval($startTimeArray[0]) > 18){
                $_SESSION['error'] = "Votre heure de début n'est pas valide.";
                header('Location: reservation-form.php');
                return;
            }
            elseif (intval($endTimeArray[0]) > 19){
                $_SESSION['error'] = "Votre heure de fin n'est pas valide.";
                header('Location: reservation-form.php');
                return;
            }
            elseif ($endTimeArray[1] != '00' || $startTimeArray[1] != '00'){
                    $_SESSION['error'] = 'Votre horaire n\'est pas valide.';
                    header('Location: reservation-form.php');
                    return;
            }
            elseif ($resDateTime < $timestampNow){
                $_SESSION['error'] = 'Vous ne pouvez pas antidater votre réservation';
                header('Location: reservation-form.php');
                return;
            }
            else{
                $dateStart = $_POST['date'] . ' ' . $_POST['startTime'] . ':00';
                $dateEnd = $_POST['date'] . ' ' . $_POST['endTime'] . ':00';
                    //LA
                $start = new DateTime($_POST['date'], new DateTimeZone('Europe/Paris'));
                $end = (clone $start)->modify('+1 day - 1 second');

                $events = new Creneaux();
                $eventsForDay = $events->getEventsBetween($start, $end);

               if (!empty($eventsForDay)){
                   $bookingStart = strtotime($dateStart);
                   $bookingEnd = strtotime($dateEnd);

                foreach($eventsForDay as $events) {
                    $eventDateStart = strtotime($events['debut']);
                    $eventDateEnd = strtotime($events['fin'] . '- 1 second');

                    if($bookingStart > $eventDateStart && $bookingStart < $eventDateEnd){
                        $_SESSION['error'] = 'Votre réservation ne peut pas être validée car une autre réservation existe déjà, commençant avant la votre dans votre créneau de temps.';
                        header('Location: reservation-form.php');
                        return; 
                    }
                    elseif ($bookingEnd > $eventDateStart && $bookingEnd < $eventDateEnd){
                        $_SESSION['error'] = 'Votre réservation ne peut pas être validée car une autre réservation existe déjà, commençant avant la votre dans votre créneau de temps.';
                        header('Location: reservation-form.php');
                        return; 
                    }
                    elseif ($bookingStart > $eventDateStart && $bookingEnd < $eventDateEnd){
                        $_SESSION['error'] = 'Votre réservation ne peut pas être validée car une autre réservation plus longue existe déjà dans votre créneau.';
                        header('Location: reservation-form.php');
                        return;
                    }
                    elseif ($bookingStart < $eventDateStart && $bookingEnd > $enventDateEnd){
                        $_SESSION['error'] = 'Votre réservation ne peut pas être validée car une autre réservation plus longue existe déjà dans votre créneau.';
                        header('Location: reservation-form.php');
                        return;
                    }
                    
                }
                
            }
        
        
                
            $insert = "INSERT INTO reservations 
            (titre, description, debut, fin, id_utilisateur) 
            VALUES (:title, :description, :debut, :fin, :id_user)";

            $stmt = $db->prepare($insert);

            $stmt->execute([
                ':title'=> htmlentities($_POST['title']),
                ':description'=> htmlentities($_POST['description']),
                ':debut'=>$dateStart,
                ':fin'=>$dateEnd,
                ':id_user'=> $_SESSION['id']
            ]);
            echo '<div class = messagegreen> Réservation enrengistrée </div>';
            


            if (isset($_POST['submit']) && empty($_SESSION['error']))  {
                $_SESSION['succes'] = "Votre reservation a reussi.";
            }
        }
        }
    }
?>

    <!DOCTYPE html>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../CSS/reservation-form.css">
    <html lang="fr">
        <body class="container">
            <main>
                <h1>Formulaire de réservation de salle</h1>
                <?php
                    if(isset($_SESSION['utilisateur'])){
                        echo " <div id='hello_profil'> Bonjour ". strtoupper($_SESSION['utilisateur']) ." reservez votre salle ici. </div>";
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<p class="error"><div class = messagered>' . $_SESSION['error'] . '</div></p>';
                        unset($_SESSION['error']);
                    }
                    elseif ( isset($_SESSION['succes']) && strlen($_POST["title"])<20) {
                        echo '<div class = messagegreen>' . 'La réservation a été enrengistrée. <br> Vous allez être redirigé vers le planning '. '</div>';
                        header('refresh: 3; URL=/reservation-salles/pages/planning.php');
                        unset($_SESSION['succes']);
                        
                    if (strlen($_POST["title"]) > 20) {
                    echo '<div class = messagered> Le titre de la réservation est trop long </div>';
                    }
                    
                    }
                    if (!isset($_SESSION['id']) || !$_SESSION['id']) :
                        echo '<p class="error">Cette partie du site où vous pourrez réaliser une réservation de salle, ne sera visible qu\'une fois connecté</p>';
                    else :
                ?>
                <div class = reservationform>
                <article id="BookingRules">
                <p>Pour pouvoir faire une réservation, vous devez respecter quelques consignes: </p>
                <ul>
                    <li>Le titre de la réservation ne doit pas dépasser 20 caractères</li>
                    <li>Vous ne pouvez pas antidater une réservation, ni reverver le jour meme,</li>
                    <li>elles sont ouvertes du Lundi au Vendredi inclus, </li>
                    <li>elles doivent débuter entre 08:00 et 18:00 inclus</li>
                    <li>et ne peuvent finir après 19:00, </li>
                    <li>Les réservations ne se font que par heures rondes: par exemple 16:00 et non pas 16:30 ou 16:59.</li>
                </ul>
                <p>
                    Si vous ne respectez pas ces règles, votre réservation ne pourra pas être validée et un message vous indiquera quelle correction devra être apportée.
                </p>
                </article>
                <div class = setreservation>
                <article id="bigbox">
                <form method="POST">
                    <label for="title"> Titre:</label>
                    <input class="BookingInput" type="text" name="title" id="title" placeholder="Entrez votre titre ici"/><br />

                    <label for="date"> Date:</label>
                    <input class="BookingInput" type="date" name="date" id="date"/><br />

                    <label for="timeStart"> Heure de début:<br /><small>de 8:00 à 19:00</small></label>
                    <input class="BookingInput" type="time" id="timeStart" name="startTime" min="08:00" max="19:00" /><br />

                    <label for="timeEnd"> Heure de fin:<br /><small>de 9:00 à 19:00</small></label>    
                    <input class="BookingInput" type="time" id="timeEnd" name="endTime" min="09:00" max="19:00" /> <br />

                    <label for="description">Description:</label> <br />
                    <textarea class="BookingInput" name="description" id="description" cols="33" rows="10" maxlength="65535"></textarea/><br />

                    <input type="submit" class="BookingInput" name='cancel' value="Annuler">
                    <input type="reset"  class="BookingInput" name='reset' value="Réinitialiser">
                    <input type="submit" class="BookingInput" name='submit' value="Valider">
                </form>
                </article>
                    </div>
                    </div>
                <?php
                    endif;
                ?>
            </main>
            
            <?php
        $path_img_footer1 = '../images/logobbYellow.png';
        $path_img_footer2 ='../images/logotomate.PNG';
        $path_footer='CSS/footer.css';
        ?>
        <footer>
        <?php
        require_once('includes/footer.html');
        ?>
        </footer>
        </body>
    </html>