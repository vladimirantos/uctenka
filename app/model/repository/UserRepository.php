<?php
namespace App\Model\Repository;

use Nette\Database\Context;

class UserRepository extends BaseRepository {
    const TABLE = "users";

    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }
}