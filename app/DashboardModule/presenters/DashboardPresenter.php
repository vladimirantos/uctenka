<?php
namespace App\DashboardModule\Presenters;
use App\Model\Service\GroupMemberService;
use App\Model\Service\GroupService;
use App\Model\Service\LoginLogService;
use App\Model\Service\LogService;
use App\Presenters\BasePresenter;

/**
 * Class AdminPresenter
 * @package App\Presenters
 * @author Vladimír Antoš
 * @version 1.0
 */
abstract class DashboardPresenter extends BasePresenter{

    /**
     * @var Identity
     */
    private $userData;

    /**
     * @var GroupMemberService @inject
     */
    public $groupMemberService;

    /**
     * @var LogService @inject
     */
    public $logService;

    /**
     * @var IRow
     */
    private $currentGroup;

    /**
     * @var LoginLogService @inject
     */
    public $loginLog;

    /**
     * @var LogService @inject
     */
    public $log;

    private $lastLogs;

    public function startup() {
        parent::startup();
        if(!$this->user->isLoggedIn())
            $this->redirect(":Login:");
        $this->template->user = ($this->userData = $this->user->identity);
        $this->template->groups = $this->groupMemberService->myGroups($this->userData->email);
        $this->template->totalGroups = $this->groupMemberService->getTotalGroupsCosts($this->userData->email);
        $this->currentGroup = $this->groupMemberService->current($this->userData->email);
        $this->template->currentGroup = $this->currentGroup;
        $this->template->lastLogs = $this->log->getLastLogs($this->user->identity->getId(),
            $this->loginLog->getLast($this->user->identity->getId())->loginDate);
    }


    public function handleChangeCurrentGroup($groupId){
        $this->groupMemberService->setCurrentGroup($groupId, $this->userData->id);
        $this->successMessage("Skupina byla změněna. Aktuální skupinou je " . $this->groupMemberService->current($this->userData->email)["groupName"]);
        $this->redirect("this");
    }
    
    /**
     * @return Identity
     */
    public function getUserData(){
        return $this->user;
    }

    /**
     * @return IRow
     */
    public function getCurrentGroup(){
        return $this->currentGroup;
    }

    public function log($text){
        $this->logService->add([
            "sender" => $this->userData->id,
            $this->currentGroup->idGroup,
            "text" => $text]);
    }

}