<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Bootstrap;
use Nette\Bootstrap\Configurator;

$configurator = Bootstrap::boot();
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/../temp/tests');

return $configurator->createContainer();