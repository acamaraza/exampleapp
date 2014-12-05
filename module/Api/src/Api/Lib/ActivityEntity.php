<?php
namespace Api\Lib;

use Api\Lib\Exception\InvalidArgumentException;
use \Api\Resources\Db\DbConnectionDecorator;


Class ActivityEntity
{

    private $connection;

    CONST ACTIVITY_TYPE_EXPENSE = 'expense';
    CONST ACTIVITY_TYPE_INCOME = 'income';

    public function __construct(DbConnectionDecorator $connection) {

        $this->connection = $connection;

    }

    /**
     * Returns the complete list of records
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll() {

        $sql = 'SELECT * FROM activities';
        return  $this->connection->query($sql);

    }

    /**
     * Returns a simple record
     *
     * @param $id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetch($id) {

        $sql = 'SELECT * FROM activities WHERE id = ?';
        return  $this->connection->query($sql, array($id));

    }

    /**
     * Inserts an activity record
     *
     * @param array $data
     * @return int|\Zend\Db\Adapter\Driver\ResultInterface
     */
    public function insert(array $data) {

        $data = array(
            ':activity_type' => isset($data['activity_type']) ? $data['activity_type'] : $this::ACTIVITY_TYPE_EXPENSE,
            ':amount' => array_key_exists('amount', $data) ? (int) $data['amount'] : 0,
            ':description' => isset($data['description']) ? $data['description'] : null
        );

        $sql = 'INSERT INTO activities (activity_type, amount, description) VALUES (:activity_type, :amount, :description)';


        $result = $this->connection->execute($sql, $data);

        if ($result->getAffectedRows() > 0) {

            return $this->connection->getLastInsertId();

        } else {

            return $result;

        }

    }

    /**
     * For record deletion, only single record supported
     *
     * @param $id
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function delete($id) {

        $sql = 'DELETE FROM activities WHERE id = ?';
        return $this->connection->execute($sql, array($id));

    }

    /**
     * For record updating, only single record supported
     *
     * @param array $data
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function update(array $data) {

        if (!isset($data['id'])) {
            throw new InvalidArgumentException('Id parameter is mandatory');
        }

        $data = array(
            ':id' => $data['id'],
            ':activity_type' => isset($data['activity_type']) ? $data['activity_type'] : $this::ACTIVITY_TYPE_EXPENSE,
            ':amount' => array_key_exists('amount', $data) ? (int) $data['amount'] : 0,
            ':description' => isset($data['description']) ? $data['description'] : null
        );

        $sql = 'UPDATE activities SET activity_type = :activity_type, amount = :amount, description = :description WHERE id = :id';

        $result = $this->connection->execute($sql, $data);

        return $result;

    }


}