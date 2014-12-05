<?php

namespace Api\Resources\Db;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
//use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;

use Api\Resources\Db\Exception\RuntimeException;

Class DbConnectionDecorator
{

    protected $dbAdapter;
    protected $transactionalConnectionResource;
    protected $transactionsInCourse;

    public function __construct(Adapter $dbAdapter){

        $this->dbAdapter = $dbAdapter;
        $this->transactionsInCourse;

    }

    /**
     * @param string $sql
     * @param array $params
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function query($sql, $params = array()){

        $result = $this->dbAdapter->query($sql, $params);

        return $result;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param string $className
     * @return bool|HydratingResultSet
     */
    public function queryWithHydratorResultSet($sql, $params = array(), $className){

        $stmt = $this->dbAdapter->createStatement($sql, $params);
        $stmt->prepare();
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new HydratingResultSet(new ReflectionHydrator, new $className);
            $resultSet->initialize($result);

            return $resultSet;

        } else {
            return false;
        }


    }

    /**
     * @param string $sql
     * @param array $params
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function execute($sql, $params = array()) {

        return $this->dbAdapter->query($sql, $params);

    }

    /**
     * @return bool
     */
    public function beginTransaction(){

        $this->transactionsInCourse++;

        if ($this->transactionsInCourse == 1) {

            $this->transactionalConnectionResource = $this->dbAdapter->getDriver()->getConnection();
            return $this->transactionalConnectionResource->beginTransaction() ? true : false;

        } else {

            return true;
        }


    }

    /**
     * @return bool
     * @throws Exception\RuntimeException
     */
    public function commit(){

        $this->transactionsInCourse--;

        if ($this->transactionsInCourse < 0) {
            throw new Exception\RuntimeException('There are no transactions in course');
        }

        if ($this->transactionsInCourse) {
            return true;
        } else {

            $result = $this->transactionalConnectionResource->commit();
            $this->transactionalConnectionResource = null;

            return $result ? true : false;
        }

    }

    /**
     * @return bool
     * @throws Exception\RuntimeException
     */
    public function rollback(){

        $this->transactionsInCourse--;

        if ($this->transactionsInCourse < 0) {
            throw new Exception\RuntimeException('There are no transactions in course');
        } else {

            // Even for anidated transactions a nested rollback implies a general rollback

            $result = $this->transactionalConnection->rollback();
            $this->transactionalConnectionResource = null;
            $this->transactionsInCourse = 0;

            return $result ? true : false;
        }

    }

    /**
     * @return int
     */
    public function getLastInsertId() {
        return $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue();
    }

    /**
     * @param \Zend\Db\Adapter\Adapter $dbAdapter
     */
    public function setDbAdapter(\Zend\Db\Adapter\Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }



}