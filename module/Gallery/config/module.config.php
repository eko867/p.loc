<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.09.18
 * Time: 11:45
 */

namespace Gallery;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [

    'doctrine' => [ //указываем на наши сущности
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'] //место хранения классов сущностей
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],

    'router' => [
        'routes' => [
            'home' => [ //домашная страница
                'type' => Literal::class,
                'options' => [
                    'route'    => '/index',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'add' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/add[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'view' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/view/album[/:id]',
                    'constraints' => [ //и ограничения записанные через регулярки
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [ //путь до файлов с представлениями(view)
            __DIR__ . '/../view',
        ],
    ],
];