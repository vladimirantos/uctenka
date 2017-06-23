<?php
namespace App\Model\Service;

use App\Model\Repository\LogRepository;

class LogService extends BaseService {

    /**
     * @param LogRepository $repository
     */
    public function __construct(LogRepository $repository) {
        parent::__construct($repository);
    }

    public function getLastLogs($email, $from){
        return $this->getRepository()->getLastLogs($email, $from);
    }
}