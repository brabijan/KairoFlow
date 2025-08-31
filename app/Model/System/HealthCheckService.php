<?php
declare(strict_types=1);

namespace App\Model\System;

use Redis;
use PDO;
use Exception;

class HealthCheckService
{
    public function __construct(
        private readonly Redis $redis,
        private readonly PDO $connection,
    ) {}

    public function checkHealth(): array
    {
        $checks = [];
        $overallStatus = 'healthy';

        $checks['database'] = $this->checkDatabase();
        if ($checks['database']['status'] !== 'healthy') {
            $overallStatus = 'unhealthy';
        }

        $checks['redis'] = $this->checkRedis();
        if ($checks['redis']['status'] !== 'healthy') {
            $overallStatus = 'unhealthy';
        }

        $checks['disk_space'] = $this->checkDiskSpace();
        if ($checks['disk_space']['status'] !== 'healthy') {
            $overallStatus = 'degraded';
        }

        $checks['php'] = $this->checkPhpVersion();

        return [
            'status' => $overallStatus,
            'timestamp' => time(),
            'checks' => $checks,
        ];
    }

    private function checkDatabase(): array
    {
        try {
            $stmt = $this->connection->query('SELECT 1');
            if ($stmt !== false) {
                return [
                    'status' => 'healthy',
                    'message' => 'Database connection successful',
                ];
            }
            return [
                'status' => 'unhealthy',
                'message' => 'Database query failed',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    private function checkRedis(): array
    {
        try {
            $pong = $this->redis->ping();
            if ($pong === true || $pong === '+PONG') {
                return [
                    'status' => 'healthy',
                    'message' => 'Redis connection successful',
                ];
            }
            return [
                'status' => 'unhealthy',
                'message' => 'Redis ping failed',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Redis connection failed: ' . $e->getMessage(),
            ];
        }
    }

    private function checkDiskSpace(): array
    {
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $percentUsed = round((($totalSpace - $freeSpace) / $totalSpace) * 100);

        if ($percentUsed > 90) {
            return [
                'status' => 'unhealthy',
                'message' => sprintf('Disk space critical: %d%% used', $percentUsed),
                'percent_used' => $percentUsed,
            ];
        }

        if ($percentUsed > 80) {
            return [
                'status' => 'degraded',
                'message' => sprintf('Disk space warning: %d%% used', $percentUsed),
                'percent_used' => $percentUsed,
            ];
        }

        return [
            'status' => 'healthy',
            'message' => sprintf('Disk space OK: %d%% used', $percentUsed),
            'percent_used' => $percentUsed,
        ];
    }

    private function checkPhpVersion(): array
    {
        return [
            'status' => 'healthy',
            'version' => PHP_VERSION,
            'required' => '^8.4',
            'message' => 'PHP version ' . PHP_VERSION,
        ];
    }
}