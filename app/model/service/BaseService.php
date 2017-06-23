<?php
namespace App\Model\Service;
use App\Model\Entity\Entity;
use App\Model\MemberAccessException;
use App\Model\Repository\IRepository;
use Nette\Database\IRow;
use Nette\Utils\Strings;

/**
 * Class BaseService
 * @package App\Model\Service
 * @author Vladimír Antoš
 * @version 1.0
 */
abstract class BaseService implements IService{

    /**
     * @var IRepository
     */
    private $repository;

    /**
     * @param IRepository $repository
     */
    public function __construct(IRepository $repository) {
        $this->repository = $repository;
    }

    public function getRepository() {
        return $this->repository;
    }

    /**
     * @param array $data
     */
    public function add(array $data) {
        $this->repository->add($data);
    }

    /**
     * @param array $data
     * @param array|null $by
     */
    public function update(array $data, array $by = null) {
        $this->repository->update($data, $by);
    }

    /**
     * @param array $where
     */
    public function delete(array $where) {
        $this->repository->delete($where);
    }

    public function getAll() {
        return $this->repository->getAll();
    }

    /**
     * @param int $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id) {
        return $this->repository->getById($id);
    }

    public function getBy(array $by) {
        return $this->repository->getBy($by);
    }

    /**
     * @return int
     */
    public function count() {
        return $this->repository->count();
    }

    /**
     * @param array $by
     * @return int
     */
    public function countBy(array $by) {
        return $this->repository->countBy($by);
    }
}