<?php
namespace App\Model\Repository;
use Nette\Database\Table\Selection;

/**
 * Interface IRepository
 * @package App\Model\Repository
 * @author Vladimír Antoš
 * @version 1.0
 */
interface IRepository {

    function add(array $data);

    function update(array $data, array $by = null);

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

    function count();

    function countBy(array $by);

}