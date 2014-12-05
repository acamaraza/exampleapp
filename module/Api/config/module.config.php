<?php
return array(

        'router' => array(
            'routes' => array(

                'api' => array(

                    'type' => 'Zend\Mvc\Router\Http\literal',
                    'options' => array(
                        'route' => '/api/v1',
                    ),

                    'may_terminate' => false,

                    'child_routes' => array(

                        'activities' => array(

                            'type' => 'segment',
                            'options' => array(
                                'route' => '/activities[/:id]',
                                'defaults' => array(
                                    'controller' => 'Api\Controller\Activity'
                                ),
                                'constraints' => array(
                                    'id' => '[0-9]{1,14}'
                                ),
                            ),
                        ),

                        // Any other API entity here

                    )


                ), // Api route ends

            ),
        ),

        'controllers' => array(
            'invokables' => array(
                'Api\Controller\Activity' => 'Api\Controller\ActivityController'
            ),
        ),

        'view_manager' => array(
            'strategies' => array(
                'ViewJsonStrategy',
            ),
        ),

        'service_manager' => array(

            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
                'Api\Resources\Db\DbConnectionDecorator' => 'Api\Resources\Db\DbConnectionDecoratorFactory',
                'Api\Lib\ActivityEntity' => 'Api\Lib\ActivityEntityFactory'
            ),

            'aliases' => array(
                'DbConnection' => 'Api\Resources\Db\DbConnectionDecorator',
                'ActivityEntity' => 'Api\Lib\ActivityEntity'
            )

        )


);