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
}