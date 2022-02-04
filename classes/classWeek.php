<?php
class Week{
    public $days =  ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    public $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    
    public $currentIsMonday;
    
    public $currentDayString;
    public $monthstring;
    
    public $currentDay;
    public $currentDate;
    public $mondayDate;
    public $day;
    public $month;
    public $year;

public function __construct(?int $day = null, ?int $month = null, ?int $year = null){
    if($day ===null || $day < 1 || $day >31){
        $day = intval(date('j'));
    }
    if($month === null || $month < 1 || $month > 12){
        $month = intval(date('m'));
    }
    if($year === null){
        $year = intval(date('Y'));
    }
    $dateString = $year . '-' . $month . '-' . $day;
    $makeDate = new DateTimeImmutable($dateString);
    $this->currentDay = intval($makeDate->format('N'));


    if($this->currentDay === 1){
        $this->mondayDate = $day;
        $this->currentIsMonday = TRUE; 
    }
    else {
        $getMondayDate = $makeDate->modify('last monday');
        $this->mondayDate = intval($getMondayDate->format('j'));
        $this->currentIsMonday = FALSE;
    }

    $this->currentDate = $dateString;
    $this->day = $day;
    $this->month = $month;
    $this->year = $year;
    

}

    public function ToString(): string {
        return $this->month[$this->month - 1] . ' ' . $this->year;

    }


     public function getDays(int $index): string{
         return $this->days[$index];
     }

 

     public function nextWeek(): Week {
         $tempDate = new DateTimeImmutable($this->currentDate);
         $dayName = $tempDate->Format('l');
         $tempDate2 = $tempDate->modify('next ' . $dayName);

         $day = $tempDate2->format('j');
         $month = $tempDate2->format('n');
         $year = $tempDate2->format('Y');

        return new Week($day, $month, $year);
     }

    public function previousWeek(): Week{
        $tempDate = new DateTimeImmutable($this->currentDate);
        $dayName = $tempDate->format('l');
        var_dump($dayName);
        $tempDate2 = $tempDate->modify('previous ' . $dayName);
        $day = $tempDate2->format('j');
        $month = $tempDate2->format('n');
        $year = $tempDate2->format('Y');

        return new Week($day, $month, $year);
    }

        public function getFirstDay(){
            return new DateTime("{$this->year}-{$this->month}-{$this->mondayDate}");
            
            
        
            
        }
}


?>