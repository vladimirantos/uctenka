<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 04.09.2016
 * Time: 15:04
 */

namespace App\Model\Repository;


use Nette\Database\Context;

class GroupMembersRepository extends BaseRepository {

    const TABLE = "group_members";

    /**
     * @param Context $context
     * @param string $table
     */
    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }


}