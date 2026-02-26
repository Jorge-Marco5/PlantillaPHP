<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;

class RateLimiter
{
    private CacheStorage $storage;

    public function __construct()
    {
        $this->storage = new CacheStorage(new FilesystemAdapter('rate_limiter', 3600, __DIR__ . '/../../var/cache'));
    }

    /**
     * Consumes a token and returns status with retry info.
     * @param string $key
     * @param int $limit
     * @param int $intervalInMinutes
     * @return object {bool accepted, int start}
     */
    public function consume(string $key, int $limit, int $intervalInMinutes = 1)
    {
        $factory = new RateLimiterFactory([
            'id' => 'limiter_' . md5($key),
            'policy' => 'token_bucket',
            'limit' => $limit,
            'rate' => ['interval' => $intervalInMinutes . ' minute', 'amount' => $limit],
        ], $this->storage);

        $limiter = $factory->create($key);
        $limit = $limiter->consume(1);

        return (object)[
            'accepted' => $limit->isAccepted(),
            'retry_after' => $limit->getRetryAfter()->getTimestamp() - time(),
            'remaining' => $limit->getRemainingTokens()
        ];
    }

    /**
     * Deprecated: Use consume instead for more details check
     */
    public function check(string $key, int $limit, int $intervalInMinutes = 1): bool
    {
        return $this->consume($key, $limit, $intervalInMinutes)->accepted;
    }
}