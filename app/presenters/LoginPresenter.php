<?php
namespace App\Presenters;
use App\Model\EntityExistsException;
use App\Model\Repository\FinancialGroupRepository;
use App\Model\Service\FinancialGroupService;
use App\Model\Service\LoginLogService;
use App\Model\Service\UserService;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;

/**
 * Interface LoginPresenter
 * @package App\Presenters
 * @author Vladimír Antoš
 * @version 1.0
 */
class LoginPresenter extends BasePresenter {

    /**
     * @var UserService @inject
     */
    public $userService;

    /**
     * @var LoginLogService @inject
     */
    public $loginLog;

    public function renderRegister(){

    }

    public function actionSignOut(){
        $this->user->logout(true);
        $this->redirect("Login:");
    }

    protected function createComponentLoginForm() {
        $form = new Form();
        $form->addText("email", "Email", null, 50);
        $form->addPassword("password", "Heslo");
        $form->addSubmit("send", "Přihlásit se");
        $form->onSuccess[] = [$this, 'loginFormSucceeded'];
        return $form;
    }

    public function loginFormSucceeded($form, $values){
        try{
            $this->user->login($values->email, $values->password);
            $this->loginLog->add(["email" => $values->email, "ipAddress" => $this->getHttpRequest()->getRemoteAddress()]);
            $this->successMessage("Přihlášení proběhlo úspěšně");
            $this->redirect("Dashboard:Homepage:");
        }catch (AuthenticationException $ex){
            $this->errorMessage($ex->getMessage());
        }
    }

    protected function createComponentRegisterForm(){
        $form = new Form();
        $form->addText("name", "Jméno", null, 40)->setRequired("Nezadal jsi jméno");
        $form->addText("email", "Email", null, 50)->setRequired("Nezadal jsi email");
        $form->addPassword("password", "Heslo")->setRequired("Nezadal jsi heslo");
        $form->addSubmit("send", "Registrovat se");
        $form->onSuccess[] = [$this, "registerFormSucceeded"];
        return $form;
    }

    public function registerFormSucceeded($form, $values){
        try{
            $this->userService->add((array)$values);
            $this->successMessage("Registrace proběhla úspěšně. Přihlaš se.");
        }catch (EntityExistsException $ex){
            $this->errorMessage("Uživatel s emailem " . $values->email . " je již registrován.");
        }
        $this->redirect("default");
    }
}