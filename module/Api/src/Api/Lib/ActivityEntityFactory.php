<?php
namespace Api\Lib;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class SessionContainerFactory
 * @package Efarmatic\Session
 *
 * This class creates and configures the sessionContainerObjects
 */
Class ActivityEntityFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $connection = $serviceLocator->get('DbConnection');
        return new ActivityEntity($connection);

    }


}
