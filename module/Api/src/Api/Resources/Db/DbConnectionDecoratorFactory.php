<?php
namespace Api\Resources\Db;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SessionContainerFactory
 * @package Efarmatic\Session
 *
 * This class creates and configures the sessionContainerObjects
 */
Class DbConnectionDecoratorFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        // The adapter should be a fully configured
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        return new DbConnectionDecorator($dbAdapter);

    }


}
