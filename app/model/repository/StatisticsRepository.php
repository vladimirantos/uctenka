<?php
namespace App\Model\Repository;

use App\Model\DateTime;
use Nette\Database\Context;

class StatisticsRepository extends BaseRepository {

    const TABLE = "payments";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }

    public function baseStats($groupId){
        $allPayments = $this->getBy(["idGroup" => $groupId]);
        $stats = new Stats();
        $stats->totalSum = $allPayments->sum("price");
        $stats->totalRows = $allPayments->count();
        $stats->thisMonth =$this->userPaymetsSummary($groupId, date('Y-m-01',strtotime('this month')),
            date('Y-m-t',strtotime('this month')));

        foreach ($stats->thisMonth as $m){
            $stats->thisMonthSum += $m->totalPrice;
        }
        $stats->totalByUser = $this->userTotal($groupId);
        $stats->maxMonth = $this->maxMonths($groupId);
        $stats->minMonth = $this->minMonths($groupId);

        $stats->maxMonth->month = $this->month($stats->maxMonth->month);
        $stats->minMonth->month = $this->month($stats->minMonth->month);
        $stats->avg = $this->monthAvgPrice($groupId)->avg;
        return $stats;
    }

    public function allPayments($groupId){
        return $this->getContext()->table("group_payments")->where(["idGroup" => $groupId])->order("paymentsDate", "desc")->fetchAll();
    }

    public function allMonthsPayments($groupId){
        return $this->getContext()->query("SELECT Year(paymentsDate) as year, Month(paymentsDate) as month, SUM(price) as price
            from payments
            where idGroup=".$groupId."
            group by Year(paymentsDate), Month(paymentsDate)")->fetchAll();
    }

    private function userPaymetsSummary($idGroup, $startDate, $endDate){
        return $this->getContext()
            ->query("SELECT userName, SUM(price) AS totalPrice 
                FROM group_payments WHERE idGroup = ".$idGroup." 
                AND paymentsDate BETWEEN '".$startDate."' and '".$endDate."' 
                GROUP BY userName ORDER BY totalPrice DESC")->fetchAll();
    }

    private function userTotal($groupId){
        return $this->getContext()
            ->query("SELECT userName, SUM(price) AS totalPrice 
                FROM group_payments WHERE idGroup = ".$groupId." 
                GROUP BY userName ORDER BY totalPrice DESC")->fetchAll();
    }

    private function maxMonths($groupId){
            return $this->getContext()->query("SELECT SUM(price) as price, 
                YEAR(paymentsDate) as year, 
                MONTH(paymentsDate) as month 
                FROM `payments` 
                WHERE idGroup=".$groupId." 
                GROUP BY YEAR(paymentsDate), 
                MONTH(paymentsDate)
                ORDER BY price DESC LIMIT 1")->fetch();
    }

    private function minMonths($groupId){
        return $this->getContext()->query("SELECT SUM(price) as price, 
                YEAR(paymentsDate) as year, 
                MONTH(paymentsDate) as month 
                FROM `payments` 
                WHERE idGroup=".$groupId." 
                GROUP BY YEAR(paymentsDate), 
                MONTH(paymentsDate)
                ORDER BY price ASC LIMIT 1")->fetch();
    }

    private function monthAvgPrice($groupId){
        return $this->getContext()->query("SELECT AVG(x.price) as avg from (SELECT SUM(price) as price, Year(paymentsDate) as year, Month(paymentsDate) as month
          from payments
          where idGroup=".$groupId."
          group by Year(paymentsDate), Month(paymentsDate)) as x
        ")->fetch();
    }

    private function month($month){
        static $months = ["leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
        return $months[$month - 1];
    }
}

class Stats{

    /**
     * @var int
     */
    public $totalRows;

    /**
     * @var int
     */
    public $totalSum;

    public $thisMonth;

    public $thisMonthSum;

    public $maxMonth;

    public $minMonth;

    public $totalByUser;
    public $avg;
}