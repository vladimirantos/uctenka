<?php
/**
 * Created by PhpStorm.
 * User: Vladimír Antoš
 * Date: 04.04.2018
 * Time: 19:40
 */

namespace App\DashboardModule\Presenters;


class StatisticsPresenter extends DashboardPresenter {

    public function actionDefault(){
        $this->template->mainTitle = "Statistiky";
    }
}