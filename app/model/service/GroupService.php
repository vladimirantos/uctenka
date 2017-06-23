<?php
namespace App\Model\Service;

use App\Model\Repository\GroupRepository;

class GroupService extends BaseService {

    /**
     * @param GroupRepository $repository
     */
    public function __construct(GroupRepository $repository) {
        parent::__construct($repository);
    }


}