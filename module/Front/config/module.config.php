<?php
return array(

    'router' => array(
        'routes' => array(

            'home' => array(

                'type' => 'Zend\Mvc\Router\Http\literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Front\Controller\Index',
                    ),
                ),

            ),

        )

    ),

    'controllers' => array(
        'invokables' => array(
            'Front\Controller\Index' => 'Front\Controller\IndexController'
        ),
    ),

    'view_manager' => array(

        'template_path_stack' => array(
            'front' => __DIR__ . '/../view',
        ),

    )



);