<?php
namespace App\Model\Service;

use App\Model\DateTime;
use App\Model\Repository\GroupMembersRepository;
use App\Model\Repository\GroupsUsersRepository;
use Nette\Database\Table\Selection;

class GroupMemberService extends BaseService {

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @var GroupMembersRepository
     */
    private $groupMembersRepository;

    /**
     * @param GroupsUsersRepository $repository
     * @param GroupService $groupService
     * @param PaymentService $paymentService
     */
    public function __construct(GroupsUsersRepository $repository,
                                GroupMembersRepository $groupMembersRepository,
                                GroupService $groupService,
                                PaymentService $paymentService) {
        parent::__construct($repository);
        $this->groupService = $groupService;
        $this->paymentService = $paymentService;
        $this->groupMembersRepository = $groupMembersRepository;
    }

    /**
     * @param array $data
     */
    public function add(array $data) {
        $this->groupService->add($data);
    }

    public function addUser($groupId, $userId){
        $this->getRepository()->addUser($groupId, $userId);
    }

    /**
     * @param string $userId
     */
    public function myGroups($userId){
        $groups = $this->getBy(["email" => $userId])->fetchAll();
        $result = [];
        foreach ($groups as $group){
            $date = DateTime::now();
            $totalCosts = $this->paymentService->userPaymetsSummary($group->idGroup, $date->getMonth() .'-'.$date->getYear());
            $group = $group->toArray();

            $x = 0;
            foreach ($totalCosts as $t)
                $x += $t["totalPrice"];
            $group["totalPrice"] = $x;
            $result[] = $group;
        }
        return $result;
    }

    /**
     * Vrací seznam všech uživatelovo skupin s celkovou útratou za celou dobu existence.
     * @param $userId
     */
    public function getTotalGroupsCosts($userId){
        $groups = $this->getBy(["email" => $userId])->fetchAll();
        $result = [];
        foreach ($groups as $group) {
            $totalCosts = $this->paymentService->getTotalCostsGroup($group->idGroup);
            $group = $group->toArray();
            $group["totalCosts"] = $totalCosts;
            $result[] = $group;
        }
        return $result;
    }

    public function myGroupsWithMembers($userId){
        $data = $this->myGroups($userId);
        $result = [];
        foreach ($data as $item) {
            $item["members"] = $this->getRepository()->getBy(["idGroup" => $item["idGroup"]])->fetchAll();
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param string $userId
     * @return Selection
     */
    public function current($userId){
        $date = DateTime::now();
        $result = $this->getBy(["email" => $userId])->where(["current" => true])->fetch()->toArray();
        $totalCosts = $this->paymentService->userPaymetsSummary($result["idGroup"], $date->getMonth() .'-'.$date->getYear());
        $x = 0;
        foreach ($totalCosts as $t)
            $x += $t["totalPrice"];
        $result["totalPrice"] = $x;
        return $result;
    }

    public function getAllUserGroups($userId){
        return $this->getRepository()->getAllUserGroups($userId);
    }

    public function getAllSharedByOwner($userId){
        return $this->getRepository()->getBy(["owner" => $userId, "type" => "shared"])->fetchAll();
    }

    public function setCurrentGroup($groupId, $userId){
        $this->groupMembersRepository->update(["current" => false], ["user" => $userId]);
        $this->groupMembersRepository->update(["current" => true], ["idGroup" => $groupId, "user" => $userId]);
    }
}