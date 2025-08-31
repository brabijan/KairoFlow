<?php
declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;

final class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator();
        $appDir = dirname(__DIR__);

        $configurator->setDebugMode(getenv('NETTE_DEBUG') === '1');
        $configurator->enableTracy($appDir . '/log');
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig($appDir . '/config/common.neon');
        $configurator->addConfig($appDir . '/config/extensions.neon');
        $configurator->addConfig($appDir . '/config/services.neon');
        
        if (file_exists($appDir . '/config/local.neon')) {
            $configurator->addConfig($appDir . '/config/local.neon');
        }

        return $configurator;
    }
}