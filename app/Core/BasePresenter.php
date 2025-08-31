<?php
declare(strict_types=1);

namespace App\Core;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    protected function startup(): void
    {
        parent::startup();
    }
}