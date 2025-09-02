<?php
declare(strict_types=1);

// Load environment variables into Nette configuration
$return = [];

// Database configuration from ENV
$dbHost = getenv('DATABASE_HOST');
if ($dbHost !== false) {
    $return['parameters']['database'] = [
        'host' => $dbHost,
        'dbname' => getenv('DATABASE_NAME') ?: 'kairoflow',
        'user' => getenv('DATABASE_USER') ?: 'kairoflow',
        'password' => getenv('DATABASE_PASSWORD') ?: '',
        'port' => (int)(getenv('DATABASE_PORT') ?: 5432),
    ];
}

// Redis configuration from ENV
$redisHost = getenv('REDIS_HOST');
if ($redisHost !== false) {
    $return['parameters']['redis'] = [
        'host' => $redisHost,
        'port' => (int)(getenv('REDIS_PORT') ?: 6379),
        'password' => getenv('REDIS_PASSWORD') ?: '',
    ];
} else {
    // Default Redis configuration for local development
    $return['parameters']['redis'] = [
        'host' => 'redis',
        'port' => 6379,
        'password' => '',
    ];
}

// MinIO/S3 configuration from ENV
$minioEndpoint = getenv('MINIO_ENDPOINT');
if ($minioEndpoint !== false) {
    $return['parameters']['minio'] = [
        'endpoint' => $minioEndpoint,
        'access_key' => getenv('MINIO_ACCESS_KEY') ?: '',
		'secret_key' => getenv('MINIO_SECRET_KEY') ?: '',
		'use_ssl' => getenv('MINIO_USE_SSL') === 'true',
	];
}

// OpenAI configuration from ENV
$openaiKey = getenv('OPENAI_API_KEY');
if ($openaiKey !== false) {
	$return['parameters']['openai'] = [
		'api_key' => $openaiKey,
	];}

// Application secrets from ENV
$appSecret = getenv('APP_SECRET');

if ($appSecret !== false) {
	$return['parameters']['app'] = [
		'secret' => $appSecret,
		'jwt_secret' => getenv('JWT_SECRET') ?: $appSecret,
    ];
}

// Environment mode
$netteEnv = getenv('NETTE_ENV');
if ($netteEnv !== false) {
    $return['parameters']['environment'] = $netteEnv;
}

// Debug mode from ENV
$netteDebug = getenv('NETTE_DEBUG');
if ($netteDebug !== false) {
    $return['parameters']['debugMode'] = $netteDebug === '1' || $netteDebug === 'true';
}

return $return;