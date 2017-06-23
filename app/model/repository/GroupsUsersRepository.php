<?php
namespace App\Model\Repository;

use Nette\Database\Context;

interface IGroupMemberRepository{
    function addUser($idGroup, $email);
}

class GroupsUsersRepository extends BaseRepository implements IGroupMemberRepository {

    const TABLE = "groups_users";

    /**
     * @param Context $context
     */
    public function __construct(Context $context) {
        parent::__construct($context, self::TABLE);
    }

    public function getAllUserGroups($userId){
        return $this->getBy(["email" => $userId])->fetchAll();
    }

    public function addUser($idGroup, $email){
        $this->getContext()->table("group_members")->insert(["idGroup" => $idGroup, "user" => $email]);
    }
}