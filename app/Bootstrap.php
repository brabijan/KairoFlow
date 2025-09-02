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
        
        // Detect console mode
        $consoleMode = PHP_SAPI === 'cli';
        $configurator->addStaticParameters([
            'consoleMode' => $consoleMode,
            'appDir' => $appDir,
            'wwwDir' => $appDir . '/public',
            'vendorDir' => $appDir . '/vendor',
            'tempDir' => $appDir . '/temp',
            'logDir' => $appDir . '/log',
        ]);

        $configurator->setDebugMode(getenv('NETTE_DEBUG') === '1');
        
        // Don't enable Tracy in console mode
        if (!$consoleMode) {
            $configurator->enableTracy($appDir . '/log');
        }
        
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        // Load configuration files
        $configurator->addConfig($appDir . '/config/common.neon');
        $configurator->addConfig($appDir . '/config/extensions.neon');
        $configurator->addConfig($appDir . '/config/services.neon');
        
        // Load environment parameters from PHP file
        $parametersFile = $appDir . '/config/parameters.php';
        if (file_exists($parametersFile)) {
            $configurator->addConfig($parametersFile);
        }
        
        // Load local configuration if exists (for development)
        if (file_exists($appDir . '/config/local.neon')) {
            $configurator->addConfig($appDir . '/config/local.neon');
        }

        return $configurator;
    }
}