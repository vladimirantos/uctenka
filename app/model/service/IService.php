<?php
namespace App\Model\Service;

use App\Model\Repository\IRepository;

interface IService {

    /**
     * @return IRepository
     */
    function getRepository();

    /**
     * @param array $data
     */
    function add(array $data);

    /**
     * @param array $data
     * @param array|null $by
     */
    function update(array $data, array $by = null);

    /**
     * @param array $where
     */
    function delete(array $where);

    /**
     * @return Selection
     */
    function getAll();

    /**
     * @param int $id
     * @return \Nette\Database\Table\IRow
     */
    function getById($id);

    /**
     * @param array $by
     * @return Selection
     */
    function getBy(array $by);

    /**
     * @return int
     */
    function count();

    /**
     * @param array $by
     * @return int
     */
    function countBy(array $by);
}