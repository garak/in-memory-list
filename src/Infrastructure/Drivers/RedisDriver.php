<?php
/**
 * This file is part of the InMemoryList package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace InMemoryList\Infrastructure\Drivers;

use InMemoryList\Infrastructure\Drivers\Contracts\DriverInterface;
use InMemoryList\Infrastructure\Drivers\Exceptions\RedisConnectionException;
use InMemoryList\Infrastructure\Drivers\Exceptions\RedisDriverCheckException;
use InMemoryList\Infrastructure\Drivers\Exceptions\RedisDriverLogicException;
use InMemoryList\Infrastructure\Drivers\Exceptions\RedisMalformedConfigException;
use Predis\Client as Redis;
use Predis\Response\ServerException;

class RedisDriver implements DriverInterface
{
    /**
     * @var
     */
    private $config;

    /**
     * @var Redis
     */
    private $instance;

    /**
     * RedisDriver constructor.
     * @param array $config
     * @throws RedisDriverCheckException
     */
    public function __construct(array $config = [])
    {
        $this->_setConfig($config);
        if (!$this->check()) {
            throw new RedisDriverCheckException('PRedis Client is not loaded.');
        }

        $this->connect();
    }

    /**
     * @param $config
     * @throws RedisMalformedConfigException
     */
    private function _setConfig($config)
    {
        $allowedConfigKeys = [
            'alias',
            'async',
            'database',
            'host',
            'iterable_multibulk',
            'options',
            'password',
            'port',
            'profile',
            'timeout',
            'path',
            'persistent',
            'read_write_timeout',
            'scheme',
            'throw_errors',
            'weight',
        ];

        foreach ($config as $key => $item) {
            if (!in_array($key, $allowedConfigKeys)) {
                throw new RedisMalformedConfigException();
            }
        }

        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function check()
    {
        if (extension_loaded('Redis')) {
            trigger_error('The native Redis extension is installed, you should use Redis instead of Predis to increase performances', E_USER_NOTICE);
        }

        return class_exists('Predis\Client');
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        $this->instance->flushall();
    }

    /**
     * @return bool
     * @throws RedisConnectionException
     */
    public function connect()
    {
        $this->instance = new Redis(array_merge([
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => null,
            'database' => null,
        ], $this->config));

        return true;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
