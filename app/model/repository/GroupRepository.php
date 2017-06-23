<?php
namespace App\Model\Repository;

use Nette\Database\Context;

class GroupRepository extends BaseRepository {

    const TABLE = "groups";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }
}