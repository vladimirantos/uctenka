<?php
namespace App\DashboardModule\Presenters;
use App\Model\ArgumentException;
use App\Model\EntityExistsException;
use App\Model\FlashTypes;
use App\Model\GroupTypes;
use App\Model\NotFoundException;
//use App\Model\Service\FinancialGroupService;
use App\Model\PairBinder;
use App\Model\Service\GroupMemberService;
use App\Model\Service\UserService;
use Nette\Application\UI\Form;

/**
 * Class FinancialGroupPresenter
 * @package App\DashboardModule\Presenters
 * @author Vladimír Antoš
 * @version 1.0
 */
class FinancialGroupPresenter extends DashboardPresenter{

    /**
     * @var []
     */
    private $sharedGroups;
//
//    /**
//     * @var FinancialGroupService @inject
//     */
//    public $financialGroupService;
//
    /**
     * @var UserService @inject
     */
    public $userService;

    /**
     * @var GroupMemberService @inject
     */
    public $groupMemberService;

    public function startup() {
        parent::startup();
    }
    
    public function actionDefault()  {
        $this->template->mainTitle = "Skupiny";
        $this->sharedGroups = PairBinder::bind($this->groupMemberService->getAllSharedByOwner($this->user->id), "idGroup", "groupName");
        $this->template->sharedGroups = $this->sharedGroups;
        $this->template->myGroups = $this->groupMemberService->myGroupsWithMembers($this->user->id);
    }
    
    protected function createComponentCreateGroupForm(){
        $form = new Form();
        $form->addText("name", "Název", null, 80)->setRequired("Nezadal jsi název skupiny")->setAttribute("placeholder", "Název skupiny");
        $form->addSelect("type", "Typ skupiny", [
            GroupTypes::PRIVATED => "Soukromá",
            GroupTypes::SHARED => "Sdílená"
        ])->setRequired("Nezadal jsi typ skupiny");
        $form->addSubmit("send", "Uložit");
        $form->onSuccess[] = [$this, "createGroupSucceeded"];
        return $form;
    }

    public function createGroupSucceeded($form, $values){
        try{
            $values = (array)$values;
            $values["owner"] = $this->getUserData()->id;
            $this->groupMemberService->add($values);
            $this->successMessage("Skupina " . $values["name"] . " byla úspěšně vytvořena.");
            $this->redirect("this");
        }catch (EntityExistsException $ex){
            $this->errorMessage("Skupinu " . $values["name"] . " jsi již dávno vytvořil. Zvol jiný název.");
        }
    }

    protected function createComponentAssignUserGroupForm(){
        $form = new Form();
        $form->addSelect("financialGroup", "Skupina",$this->sharedGroups)->setRequired("Nevybral jsi skupinu");
        $form->addText("user", "Uživatel")->setRequired("Nezadal jsi uživatele")
            ->setAttribute("placeholder", "Email uživatele");
        $form->addSubmit("send", "Uložit");
        $form->onSuccess[] = [$this, "assignUserGroupSucceeded"];
        return $form;
    }

    public function assignUserGroupSucceeded($form, $values){
        try {
            if(!$this->userService->exists($values->user))
                throw new NotFoundException("Uživatel s emailem ". $values->user . " neexistuje");
            $this->groupMemberService->addUser($values->financialGroup, $values->user);
            $this->successMessage("Uživatel ". $values->user . " byl přidán do skupiny");
        } catch (NotFoundException $ex) {
            $this->errorMessage($ex->getMessage());
        } catch (EntityExistsException $ex){
            $this->errorMessage($ex->getMessage());
        }catch (ArgumentException $ex){
            $this->errorMessage($ex->getMessage());
        }
        $this->redirect("this");
    }

    public function handleDelete($idGroup, $user){
        $this->groupMemberService->removeUserFromGroup($idGroup, $user);
        $this->successMessage("Uživatel ". $user . " byl odebrán ze skupiny");
        $this->redirect("this");
    }
}