<?php
namespace App\Model\Service;

use App\Model\Repository\GroupPaymentRepository;
use App\Model\Repository\PaymentRepository;

class PaymentService extends BaseService{

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param IPaymentRepository $repository
     */
    public function __construct(GroupPaymentRepository $repository, PaymentRepository $paymentRepository) {
        parent::__construct($repository);
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param array $data
     */
    public function add(array $data) {
        $p = explode(". ", $data["paymentsDate"]);
        $data["paymentsDate"] = $p[2].'-'.$p[1].'-'.$p[0];
        $this->paymentRepository->add($data);
    }


    public function getAllUsersGroups($userId, $groupId){
        $data = $this->getBy(["email" => $userId, "idGroup" => $groupId])->order("idGroup ASC, paymentsDate DESC");
        $totalCosts = 0;
        foreach ($data as $d)
            $totalCosts += $d["price"];
        $data["totalPrice"] = $totalCosts;
        return $data;
    }

    public function getAllGroupPayments($groupId){
        return $this->getRepository()->getBy(["idGroup" => $groupId])->order("paymentsDate DESC")->fetchAll();
    }

    public function getAllUserPaymets($userId){
        return $this->getRepository()->getBy(["email" => $userId])->fetchAll();
    }

    public function getPaymentsInAllGroups($userId){

    }

    public function getMonthly($idGroup, $date){
        return $this->getRepository()->getMonthly($idGroup, $date);
    }

    public function userPaymetsSummary($idGroup, $date){
        return $this->getRepository()->userPaymetsSummary($idGroup, $date);
    }

    public function getTotalCosts($groupId, $dateTime){
        return $this->getRepository()->getTotalCosts($groupId, $dateTime);
    }

    public function delete(array $where){
        $this->paymentRepository->delete($where);
    }

    public function getById($id) {
        return $this->paymentRepository->getById($id);
    }

    public function update(array $data, array $by = null) {
        $this->paymentRepository->update($data, $by);
    }
}