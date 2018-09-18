<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'doctrine' => [
        // настройка миграций
        'migrations_configuration' => [
            'orm_default' => [ //стандартное имя менеджера сущностей (см doctrine.local.php)
                'directory' => 'data/Migrations', //место хранения миграций
                'name'      => 'Doctrine Database Migrations', //удобное имя для миграций
                'namespace' => 'Migrations', //хотим содержать классы миграций в пространстве имен Migrations
                'table'     => 'migrations', //хотим хранить историю миграций в таблице базы данных migrations. Doctrine автоматически создаст эту таблицу и будет ей управлять.
            ],
        ],
    ],
];
