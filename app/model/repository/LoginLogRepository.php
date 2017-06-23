<?php
namespace App\Model\Repository;

use Nette\Database\Context;

class LoginLogRepository extends BaseRepository {
    const TABLE = "login_log";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }
}