<?php
namespace App\Presenters;
use App\Model\FlashTypes;
use Nette\Application\UI\Presenter;

/**
 * Class BasePresenter
 * @package App\Presenters
 * @author Vladimír Antoš
 * @version 1.0
 */
class BasePresenter extends Presenter{

    public function startup() {
        parent::startup();
    }

    public function errorMessage($message){
        $flash = $this->flashMessage($message, FlashTypes::ERROR);
        $flash->title = "Chyba!";
        return $flash;
    }

    public function successMessage($message){
        $flash = $this->flashMessage($message, FlashTypes::SUCCESS);
        $flash->title = "Výborně!";
        return $flash;
    }

    public function infoMessage($message){
        $flash = $this->flashMessage($message, FlashTypes::INFO);
        $flash->title = "Pozor!";
        return $flash;
    }

    public function warningMessage($message){
        $flash = $this->flashMessage($message, FlashTypes::WARNING);
        $flash->title = "Pozor!";
        return $flash;
    }
}