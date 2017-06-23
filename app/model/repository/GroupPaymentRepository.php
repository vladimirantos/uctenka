<?php
namespace App\Model\Repository;


use Nette\Database\Context;

interface IPaymentRepository{
    function getTotalCosts($groupId, \App\Model\DateTime $dateTime);
    function getMonthly($idGroup, $date);
}

class GroupPaymentRepository extends BaseRepository implements IPaymentRepository {

    const TABLE = "group_payments";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }

    public function getTotalCosts($groupId, \App\Model\DateTime $dateTime){
        $payments = $this->getBy(["idGroup" => $groupId, "paymentsDate" => $dateTime->toString()]);
        $total = 0;
        foreach ($payments as $p)
            $total += $p->price;
        return $total;
    }

    public function getMonthly($idGroup, $date){
        $date = explode("-", $date);
        $startDate = $date[1]. '-' . $date[0] . '-01';
        $endDate = date("Y-m-t", strtotime($startDate));
        return $this->getBy(
            ["idGroup" => $idGroup, "paymentsDate BETWEEN ? AND ?" => [$startDate, $endDate]])
            ->order("paymentsDate DESC")->fetchAll();
    }

    public function userPaymetsSummary($idGroup, $date){
        $date = explode("-", $date);
        $startDate = $date[1]. '-' . $date[0] . '-01';
        $endDate = date("Y-m-t", strtotime($startDate));
        return $this->getContext()
            ->query("SELECT userName, SUM(price) AS totalPrice 
                FROM group_payments WHERE idGroup = ".$idGroup." 
                AND paymentsDate BETWEEN '".$startDate."' and '".$endDate."' 
                GROUP BY userName ORDER BY totalPrice DESC")->fetchAll();
    }
}