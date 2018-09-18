<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.09.18
 * Time: 11:48
 */

namespace Gallery;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}