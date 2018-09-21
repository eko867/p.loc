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

    'router' => [ //настраиваем роутинг
        'routes' => [

            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'albums' => [ //cписок альбомов
                'type' => Literal::class,
                'options' => [
                    'route'    => '/albums',
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],

                'may_terminate' => true,

                'child_routes' => [
                    'create' => [ //страница cоздания альбома
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'create',
                            ],
                        ],
                    ],

                    'addphoto' => [ //страница добавления фото с первоначальным выбором альбома
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/addphoto',
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'addPhoto',
                            ],
                        ],
                    ],

                    'alb' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/:idAlbum[/:action]', #action: edit, delete, view, newPhoto)
                            'constraints' => [ //ограничения записанные через регулярки
                                'action' => '[a-zA-Z]*',
                                'idAlbum' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'view',
                            ],
                        ],

                        'may_terminate' => true,

                            'child_routes' =>[

                                'photos_show' => [ // работа с фотографиями
                                    'type' => Segment::class,
                                    'options' => [
                                        'route'    => '/photos/:idPhoto',
                                        'constraints' => [ //ограничения записанные через регулярки
                                            'idPhoto' => '[0-9]+',
                                        ],
                                        'defaults' => [
                                            'controller' => Controller\AlbumController::class,
                                            'action'     => 'showPhoto',
                                        ],
                                    ],
                                ],

                                'photos_delete' => [ // работа с фотографиями
                                    'type' => Segment::class,
                                    'options' => [
                                        'route'    => '/photos/:idPhoto/delete',
                                        'constraints' => [ //ограничения записанные через регулярки
                                            'idPhoto' => '[0-9]+',
                                        ],
                                        'defaults' => [
                                            'controller' => Controller\AlbumController::class,
                                            'action'     => 'deletePhoto',
                                        ],
                                    ],
                                ],
                                'photos_edit' => [ // работа с фотографиями
                                    'type' => Segment::class,
                                    'options' => [
                                        'route'    => '/photos/:idPhoto/edit',
                                        'constraints' => [ //ограничения записанные через регулярки
                                            'idPhoto' => '[0-9]+',
                                        ],
                                        'defaults' => [
                                            'controller' => Controller\AlbumController::class,
                                            'action'     => 'editPhoto',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                ]
            ],

        ],//end routes
     ],//end router

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AlbumController::class => Controller\Factory\AlbumControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\AlbumManager::class => Service\Factory\AlbumManagerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [ //путь до файлов с представлениями(view)
            __DIR__ . '/../view',
        ],
    ],
];