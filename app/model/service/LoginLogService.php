<?php
namespace App\Model\Service;

use App\Model\Repository\LoginLogRepository;

class LoginLogService extends BaseService {

    /**
     * @param LoginLogRepository $repository
     */
    public function __construct(LoginLogRepository $repository) {
        parent::__construct($repository);
    }

    public function getLast($email){
        return $this->getRepository()->getBy(["email" => $email])->order("loginDate DESC")->fetch();
    }
}