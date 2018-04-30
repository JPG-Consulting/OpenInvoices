<?php

namespace  OpenInvoices;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Mvc\Controller\Plugin\CreateHttpForbiddenModel::class => InvokableFactory::class,
        ],
        'aliases' => [
            'createForbiddenModel' => Mvc\Controller\Plugin\CreateHttpForbiddenModel::class,
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\AuthenticationController::class => Service\AuthenticationControllerFactory::class,
        ],
    ],
    'listeners' => [
        EventManager\RouteListene::class,
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\AuthenticationServiceFactory::class,
            \Zend\Session\Config\ConfigInterface::class => \Zend\Session\Service\SessionConfigFactory::class,
            EventManager\RouteListene::class => Service\RouteListenerFactory::class,
            \Zend\Permissions\Rbac\Rbac::class => Service\RbacFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/unauthenticated'  => __DIR__ . '/../view/layout/unauthenticated.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];