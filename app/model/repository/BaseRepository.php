<?php
namespace App\Model\Repository;
use App\Model\EntityExistsException;
use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\Database\UniqueConstraintViolationException;

/**
 * Class BaseRepository
 * @package App\Model\Repository
 * @author Vladimír Antoš
 * @version 1.0
 */
abstract class BaseRepository implements IRepository{

    /**
     * @var Context
     */
    private $db;

    /**
     * @var string
     */
    private $table;

    /**
     * @param Context $context
     * @param string $table
     */
    public function __construct(Context $context, $table) {
        $this->db = $context;
        $this->table = $table;
    }

    /**
     * @return Context
     */
    protected function getContext(){
        return $this->db;
    }

    /**
     * @return string
     */
    protected function getTable(){
        return $this->table;
    }
    
    /**
     * @param array $data
     * @return bool|int|\Nette\Database\Table\IRow
     * @throws EntityExistsException
     */
    public function add(array $data) {
        try{
            return $this->db->table($this->table)->insert($data);
        }catch (UniqueConstraintViolationException $ex){
            if($ex->getCode() == 23000)
                throw new EntityExistsException("Tato položka je již v databázi");
        }
        return null;
    }

    /**
     * @param array $data
     * @param array $by
     */
    public function update(array $data, array $by = null) {
        $this->db->table($this->table)->where($by)->update($data);
    }

    /**
     * @param array $where
     */
    public function delete(array $where) {
        $this->db->table($this->table)->where($where)->delete();
    }

    /**
     * @return Selection
     */
    public function getAll() {
        return $this->db->table($this->table);
    }

    /**
     * @param int $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id) {
        return $this->db->table($this->table)->get($id);
    }

    /**
     * @param array $by
     * @return Selection
     */
    public function getBy(array $by) {
        return $this->db->table($this->table)->where($by);
    }

    /**
     * @return int
     */
    public function count() {
        return $this->db->table($this->table)->count();
    }

    /**
     * @param array $by
     * @return int
     */
    public function countBy(array $by) {
        return $this->db->table($this->table)->where($by)->count();
    }
}