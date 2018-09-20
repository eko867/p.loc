<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 9:51
 */

namespace Gallery\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Gallery\Service\AlbumManager;
use Gallery\Controller\AlbumController;

class AlbumControllerFactory implements  FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $albumManager = $container->get(AlbumManager::class);

        // inject dependencies
        return new AlbumController($entityManager, $albumManager);
    }
}