<?php
namespace Api\Lib;

use \Api\Resources\Db\DbConnectionDecorator;


Class ActivityEntity
{

    private $connection;

    CONST ACTIVITY_TYPE_EXPENSE = 'expense';
    CONST ACTIVITY_TYPE_INCOME = 'income';

    public function __construct(DbConnectionDecorator $connection) {

        $this->connection = $connection;

    }

    public function fetchAll() {

        $sql = 'SELECT * FROM activities';
        return  $this->connection->query($sql);

    }

    public function fetch($id) {

        $sql = 'SELECT * FROM activities WHERE id = ?';
        return  $this->connection->query($sql, array($id));


    }

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

    public function delete($id) {

        $sql = 'DELETE FROM activities WHERE id = ?';
        return $this->connection->execute($sql, array($id));

    }

    public function update($id) {}


}