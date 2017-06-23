<?php
namespace App\DashboardModule\Presenters;


use App\DashboardModule\Presenters\DashboardPresenter;
//use App\Model\Service\FinancialGroupService;
use App\Model\PairBinder;
use App\Model\Repository\GroupPaymentRepository;
use App\Model\Service\GroupService;
use App\Model\Service\PaymentService;
use Nette\Application\UI\Form;

class ReportPresenter extends DashboardPresenter {

    /**
     * @var PaymentService @inject
     */
    public $paymentService;

    public function startup() {
        parent::startup();
    }

    public function renderDefault(){
        $this->template->mainTitle = "Měsíční přehledy";
    }

    protected function createComponentReportForm(){
        $groups = PairBinder::bind($this->groupMemberService->getAllUserGroups($this->user->identity->getId()), "idGroup", "groupName");
        $form = new Form();
        $form->addSelect("financialGroups", "Skupina", $groups)->setDefaultValue($this->getCurrentGroup()["idGroup"]);
        $form->addText("date", "Měsíc")->setAttribute("placeholder", "Vyber měsíc");
        $form->addSubmit("send", "Vygenerovat");
        $form->onSuccess[] = [$this, "reportFormSucceeded"];
        return $form;
    }

    public function reportFormSucceeded($form, $values){
        $this->redirect("report!", ["idGroup" => $values->financialGroups, "date" => $values->date]);
    }

    public function handleReport($idGroup, $date){
        $this->template->reportData = $this->paymentService->getMonthly($idGroup, $date);
        if($this->template->reportData == null)
            $this->warningMessage("Pro měsíc ".$date." nejsou žádné platby");
        $paymentsSummary = $this->paymentService->userPaymetsSummary($idGroup, $date);
        $this->template->userSummary = $paymentsSummary;
        $total = 0;
        foreach ($paymentsSummary as $item) {
            $total += $item["totalPrice"];
        }
        $this->template->totalPrice = $total;
    }
}