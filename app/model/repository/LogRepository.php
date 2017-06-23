<?php
namespace App\Model\Repository;

use App\Model\DateTime;
use Nette\Database\Context;

class LogRepository extends BaseRepository {

    const TABLE = "log";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }

    public function getLastLogs($email, $from){
        return $this->getContext()->query("
          SELECT l.*, u.name, gr.name as groupName FROM log AS l
          JOIN users AS u ON u.email = l.sender
          JOIN groups AS gr ON gr.idGroup=l.idGroup
          WHERE l.idGroup IN (
            SELECT g.idGroup FROM group_members AS gm 
            JOIN groups AS g ON g.idGroup = gm.idGroup
            WHERE gm.user='".$email."' AND g.type='shared') 
          AND sender != '".$email . "' AND l.creationDate <= '".$from."' order by creationDate DESC LIMIT 5")->fetchAll();
    }
}