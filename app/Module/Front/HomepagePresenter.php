<?php
declare(strict_types=1);

namespace App\Module\Front;

use App\Core\BasePresenter;

final class HomepagePresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->title = 'KairoFlow - ADHD-Optimized Financial & Task Management';
        $this->template->appName = 'KairoFlow';
    }
}