<?php
declare(strict_types=1);

namespace App\Module\Api;

use App\Core\BasePresenter;
use App\Model\System\HealthCheckService;
use Nette\Application\Responses\JsonResponse;

final class HealthPresenter extends BasePresenter
{
    public function __construct(
        private readonly HealthCheckService $healthCheckService,
    ) {
        parent::__construct();
    }

    public function actionDefault(): void
    {
        $health = $this->healthCheckService->checkHealth();
        
        $httpCode = match($health['status']) {
            'healthy' => 200,
            'degraded' => 200,
            'unhealthy' => 503,
            default => 500,
        };
        
        $this->getHttpResponse()->setCode($httpCode);
        $this->sendResponse(new JsonResponse($health));
    }
}