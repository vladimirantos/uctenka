<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 03.09.2016
 * Time: 18:49
 */

namespace App\Model\Repository;


use Nette\Database\Context;

class PaymentRepository extends BaseRepository {

    const TABLE = "payments";

    /**
     * @param Context $context
     * @param string $table
     */
    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }

    public function totalCosts($idGroup){
        return $this->getContext()->table(self::TABLE)->where(["idGroup" => $idGroup])->sum("price");
    }
}