<?php
declare(strict_types=1);

namespace App\Core;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

final class RouterFactory
{
    public static function createRouter(): RouteList
    {
        $router = new RouteList();
        
        // API routes
        $router->addRoute('/api/health', 'Api:Health:default');
        
        // Frontend routes
        $router->addRoute('/', 'Front:Homepage:default');
        $router->addRoute('/<presenter>/<action>[/<id>]', [
            'module' => 'Front',
            'presenter' => 'Homepage',
            'action' => 'default',
        ]);
        
        return $router;
    }
}