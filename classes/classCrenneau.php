<?php
class Creneaux {
    private $pdo;
    public $lengthEvents = [];
    public $eventfortheWeek = [];

    public function __construct(){
        $this->pdo = new PDO('mysql:host=localhost;dbname=reservationsalles', 'root', '', [

            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

    }
    public function getEventsBetween(DateTime $start, DateTime $end): array {
        $sql = "SELECT reservations.id, reservations.titre, reservations.debut, reservations.fin, utilisateurs.login 
        FROM reservations JOIN utilisateurs ON utilisateurs.id = reservations.id_utilisateur 
        WHERE reservations.debut 
        BETWEEN '{$start->format('Y--d 08:00:00')}' AND '{$end->format('Y-m-d 19:00:00')}'";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll();
        return $results;
    }

    public function getEventsBetweenByDay(DateTime $start, DateTime $end): array {
        $events = $this->getEventsBetween($start, $end);
        foreach ($events as $event) {
            $date = explode(' ', $event['debut'])[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            }
            else{
                $days[$date][] = [$event];
            }
        }
        return $days;
    }

    public function getEventsBetweenByDayTime(DateTime $start, DateTime $end): array{
        $events = $this->getEventsBetween($start, $end);
        $days = [];
        foreach ($events as $event){
            $days[$event['debut']] = $event;
            
            $diff = new Creneaux;
            $length = $diff->timeLength($event['debut'], $event['fin']);

            $days[$event['debut']]['length'] = $length;
            $dateStart = new DateTime($event['debut']);
            $dateDay = $dateStart->format('N');
            $timeHour = $dateStart->format('G');
            $case = ($timeHour - 7) . '-' . $dateDay;
            $days[$event['debut']]['case'] = $case;
            $lengthEvents[$case] = $length;

        }
        return $days;
    }
    public function timeLength(string $start, string $end): int {
        $tempOne = new DateTime($start);
        $tempTwo = new DateTime($end);
        $length = date_diff($tempOne, $tempTwo);
        return $length->h;
    }
    public function getEventById(int $id): array {
        
        $sql = "SELECT reservations.id, reservations.titre, reservations.description, reservations.debut, reservations.fin, utilisateurs.login
            FROM reservations JOIN utilisateurs
            ON utilisateurs.id = reservations.id_utilisateur
            WHERE reservations.id = $id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id'=>$id]);
        
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
       


        
        return $results;
    
    }
    public function Validateform($title, $date, $start, $end, $text){}


}
?>