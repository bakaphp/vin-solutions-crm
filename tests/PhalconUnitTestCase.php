<?php

use function Baka\envValue;
use Baka\TestCase\PhalconUnit;
use Phalcon\Config;
use Phalcon\Di;

class PhalconUnitTestCase extends PhalconUnit
{
    /**
     * Set configuration.
     *
     * @return void
     */
    protected function setConfiguration() : void
    {
        $this->config = new Config([
            'database' => [
                'adapter' => 'Mysql',
                'host' => getenv('DATA_API_MYSQL_HOST'),
                'username' => getenv('DATA_API_MYSQL_USER'),
                'password' => getenv('DATA_API_MYSQL_PASS'),
                'dbname' => getenv('DATA_API_MYSQL_NAME'),
            ]
        ]);
    }

    /**
     * Setup phalconPHP DI.
     *
     * @return void
     */
    protected function configureDI() : void
    {
        $config = $this->config;

        $this->di->setShared('config', fn () => $config);


        $this->di->setShared(
            'redis',
            function () {
                $redis = new Redis();
                $redis->connect(envValue('REDIS_HOST', 'redis'));
                $serializeEngine = !extension_loaded('igbinary') ? Redis::SERIALIZER_PHP : Redis::SERIALIZER_IGBINARY;
                $redis->setOption(Redis::OPT_SERIALIZER, $serializeEngine);
                return $redis;
            }
        );
    }

    /**
     * this runs before everyone.
     */
    protected function setUp() : void
    {
    }
}
