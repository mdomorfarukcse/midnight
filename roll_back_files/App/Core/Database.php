<?php

namespace Pemm\Core;

use Pemm\Utils\Criteria;
use Pemm\Utils\Filter;
use Pemm\Utils\OrderBy;

use PDO;

use Pemm\Config;

class Database extends PDO
{
    private static $instance = null;

    const OPTION = array(
        PDO::ATTR_PERSISTENT => FALSE,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    public function __construct()
    {
        parent::__construct(
            "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME,
            Config::DB_USER,
            Config::DB_PASSWORD,
            self::OPTION
        );
    }

    public static function getInstance()
    {
        global $container;

        self::$instance = $container->has('database');

        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }


    public function find($table, $id)
    {
        $criteria = new Criteria();
        $criteria->filter->add('id', $id, '=');

        return $this->findOneBy($table, $criteria);
    }

    public function findOneBy($table, $criteria)
    {
        return $this->findBy($table, $criteria,true);
    }

    public function findAll($table, $criteria)
    {
        return $this->findBy($table, $criteria);
    }

    public function findBy($table, $criteria, $findOne = false)
    {
        $where = [];
        $executeData = [];
        $order = [];

        /* @var Filter $filter */
        foreach ($criteria->filter->getList() as $filter) {
            if (is_array($filter->getValue())) {
                $where[] = $table . '.' . $filter->getField() . ' IN (' . implode(',', $filter->getValue()) . ')';
            } else {
                $where[] = $table . '.' . $filter->getField() . $filter->getOperator() . ':' . $filter->getField();
                $executeData[':' . $filter->getField()] = $filter->getValue();
            }
        }

        $sql = 'SELECT * FROM ' . $table;

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        print_r($sql);diE;

        /* @var OrderBy $orderBy */
        foreach ($criteria->orderBy->getList() as $orderBy) {
            $order[] = $table . '.' . $orderBy->getField() . ' ' . $orderBy->getSort();
        }

        if ($criteria->pagination->has()) {

            try {

                $prepare = $this->prepare($sql);
                $prepare->execute($executeData);

                $criteria->pagination->setItemCount($prepare->rowCount());

            } catch (\Exception $e) {}

            $sql .= ' LIMIT ' . $criteria->pagination->getLimit();
            $criteria->pagination->setPageCount(ceil($criteria->pagination->getItemCount() / $criteria->pagination->getLimit()));

            if (!empty($criteria->pagination->getPage())) {
                $sql .= ' OFFSET ' . (($criteria->pagination->getPage() - 1) * $criteria->pagination->getLimit());
            }

        }

        try {

            $prepare = $this->prepare($sql);
            $prepare->execute($executeData);

            if ($findOne) {
                $result = $prepare->fetchObject();
            } else {
                $result = $prepare->fetchAll(PDO::FETCH_OBJ);
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    public function bulkInsert($table, $data): bool
    {
        try {

            $inserted = false;
            $rows = $toBind = $columns = [];

            foreach($data as $key => $row){
                $params = [];
                foreach((array) $row as $column => $value){
                    $columns[$column] = true;
                    $param = ":" . $column . $key;
                    $params[] = $param;
                    $toBind[$param] = $value;
                }
                $rows[] = "(" . implode(", ", $params) . ")";
            }

            unset($data);

            $stmt = self::getInstance()->prepare("INSERT IGNORE INTO `$table` (" . implode(", ", array_keys($columns)) . ") VALUES " . implode(", ", $rows));

            foreach($toBind as $param => $val){
                $stmt->bindValue($param, $val);
            }

            $inserted = $stmt->execute();

        } catch (\PDOException $e) { print_r($e);
        } catch (\Exception $e) { print_r($e); }

        return $inserted;
    }

    public function bulkDeleteByIds($table, $ids): bool
    {
        $stmt = $this->prepare("DELETE FROM " . $table . " WHERE id IN (".str_repeat("?,", count($ids) - 1)."?)");

        return $stmt->execute($ids);
    }

    public function bulkDeleteByColumn($table, $column, $values): bool
    {
        $stmt = $this->prepare("DELETE FROM " . $table . " WHERE " . $column . " IN (".str_repeat("?,", count($values) - 1)."?)");

        return $stmt->execute($values);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function update($table, $data): bool
    {
        $updateData = $updateFields = [];

        foreach ($data as $key => $value) {
            if ($key != 'id') {
                $updateFields[] = $key . '=:' . $key;
            }
            $updateData[':' . $key] = $value;
        }

        $update = $this->prepare('UPDATE ' . $table . ' SET ' . implode(', ', $updateFields) . ' WHERE id=:id');

        return $update->execute($updateData);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function insert($table, $data): int
    {
        $insertData = $insertFields = [];

        foreach ($data as $key => $value) {
            $insertFields[] = $key;
            $insertData[':' . $key] = trim($value);
        }

        $insert = $this->prepare('INSERT INTO ' . $table . ' (' . implode(', ', $insertFields) . ') VALUES (:' . implode(', :', $insertFields) . ')');

        return $insert->execute($insertData);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function count($table, $data): int
    {
        $select = $selectData = [];

        foreach ($data as $key => $value) {
            $select[] = $key . '=:' . $key;
            $selectData[':' . $key] = trim($value);
        }

        $count = $this->prepare('SELECT * FROM ' . $table . ' WHERE ' . implode(', ', $select));
        $count->execute($selectData);

        return $count->rowCount();
    }

}
