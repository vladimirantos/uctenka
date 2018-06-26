<?php
namespace App\Model\Service;


use App\Model\Repository\IRepository;
use App\Model\Repository\StatisticsRepository;

class StatisticsService extends BaseService {

    public function __construct(StatisticsRepository $repository) {
        parent::__construct($repository);
    }

    public function baseStats($groupId){
        return $this->getRepository()->baseStats($groupId);
    }

    public function allPayments($groupId){
        return $this->getRepository()->allPayments($groupId);
    }

    public function allMonthPayments($groupId){
        return $this->getRepository()->allMonthsPayments($groupId);
    }
}