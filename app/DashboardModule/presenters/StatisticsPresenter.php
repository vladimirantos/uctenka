<?php
namespace App\DashboardModule\Presenters;

use App\Model\Service\StatisticsService;

class StatisticsPresenter extends DashboardPresenter {

    /**
     * @var StatisticsService @inject
     */
    public $statisticsService;

    public function actionDefault(){
        $this->template->mainTitle = "Statistiky pro skupinu ".$this->getCurrentGroup()["groupName"];
        $groupId = $this->getCurrentGroup()["idGroup"];
        $this->template->baseStats = $this->statisticsService->baseStats($groupId);
        $this->template->allPayments = $this->statisticsService->allPayments($groupId);
    }

    public function actionChart(){
        $this->sendResponse(new \Nette\Application\Responses\JsonResponse($this->statisticsService->allMonthPayments($this->getCurrentGroup()["idGroup"])));
    }
}