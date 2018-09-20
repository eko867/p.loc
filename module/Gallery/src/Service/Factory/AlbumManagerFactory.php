<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 9:28
 */

namespace Gallery\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Gallery\Service\AlbumManager;
/**
 * Фабрика для инстанцирования сервиса AlbumManager
 */

class AlbumManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Инстанцирует менеджер (т.о внедрили зависимость от entityManager'а)
        return new AlbumManager($entityManager);
    }
}