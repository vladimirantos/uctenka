<?php
namespace App\DashboardModule\Presenters;
use App\Model\ArgumentException;
use App\Model\DateTime;
use App\Model\PairBinder;
use App\Model\PaymentsException;
use App\Model\Service\GroupMemberService;
use App\Model\Service\GroupService;
use App\Model\Service\LoginLogService;
use App\Model\Service\LogService;
use App\Model\Service\PaymentService;
use Nette\Application\UI\Form;


/**
 * Class HomepagePresenter
 * @package App\DashboardModule\Presenters
 * @author Vladimír Antoš
 * @version 1.0
 */
class HomepagePresenter extends DashboardPresenter{

    /**
     * @var PaymentService @inject
     */
    public $paymentService;

    /**
     * @var GroupMemberService @inject
     */
    public $groupMemberService;

    public $idPayment;

    public function startup() {
        parent::startup();
    }

    public function actionDefault(){
        $this->template->mainTitle = "Nástěnka";
        $currentMonth = DateTime::now();
        $this->template->payments =
            $this->paymentService->getMonthly($this->getCurrentGroup()["idGroup"],
                $currentMonth->getMonth() . '-'.$currentMonth->getYear());
        $myPayments = array_filter($this->template->payments, function($i){return $i->userName == $this->getUserData()->identity->name;});
        $this->template->sumMyPayments = array_sum(array_map(function($item){return $item->price;}, $myPayments));
        $this->template->totalCostsInGroup = $this->paymentService->getTotalCostsGroup($this->getCurrentGroup()["idGroup"]);
    }

    protected function createComponentAddPaymentForm() {
        $groups = PairBinder::bind($this->groupMemberService->getAllUserGroups($this->user->identity->getId()), "idGroup", "groupName");
        $form = new Form();
        $form->addSelect("idGroup", "Skupina", $groups)
            ->setDefaultValue($this->getCurrentGroup()["idGroup"])
            ->setRequired("Nevybral jsi skupinu.");
        $form->addText("description", "Popis")->setAttribute("placeholder", "Popis")->setRequired("Nezadal jsi popis platby.");
        $form->addText("price", "Cena")->setAttribute("placeholder", "Cena")->setRequired("Nezadal jsi cenu.");
        $form->addText("paymentsDate", "Datum")->setAttribute("placeholder", "Datum platby")->setRequired("Nezadal jsi datum.");
        $form->addHidden("user", $this->user->id);
        $form->addSubmit("send", "Přidat platbu");
        $form->onSuccess[] = [$this, "addPaymentFormSucceeded"];
        return $form;
    }

    public function addPaymentFormSucceeded($form, $values){
      try{
              $this->paymentService->add((array)$values);
              $this->log->add(["sender" => $this->user->identity->getId(),
                  "idGroup" => $values->idGroup,
                  "text" => "Přidána platba v hodnotě " . $values->price . " Kč"]);
              $this->successMessage("Platba " . $values->price . ' Kč byla přidána.');
      }catch (PaymentsException $ex){
          $this->errorMessage($ex->getMessage());
      }
        $this->redirect("this");
    }

    public function handleShowAllGroups(){
        $this->template->payments = $this->paymentService->getAllUserPaymets($this->user->id);
     //   $this->redirect("this");
    }

    public function handleShowAll(){
        $this->template->payments = $this->paymentService->getAllGroupPayments($this->getCurrentGroup()["idGroup"]);
    }

    public function handleDeletePayment($idPayment){
        $this->paymentService->delete(["idPayment" => $idPayment]);
        $this->successMessage("Platba byla úspěšně smazána.");
        $this->redirect('Homepage:');
    }

    public function handleEditPayment($idPayment){
        $this->idPayment = $idPayment;
        $data = $this->paymentService->getById($idPayment)->toArray();
        if(!$data){
            $this->errorMessage("Tato platba neexistuje");
            $this->redirect("this");
        }
        $data["paymentsDate"] = $data["paymentsDate"]->format("d.m.Y");
        $this["addPaymentForm"]->setDefaults($data);
    }
}