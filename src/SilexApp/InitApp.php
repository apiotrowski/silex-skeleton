<?php

namespace SilexApp;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Yaml\Parser;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;

/**
 * Class Main
 * @package SilexApp\Main
 */
class InitApp implements ControllerProviderInterface
{
    const RABBIT = 'rabbitmq';

    /**
     * @param Application $app
     * @return Response
     */
    public function setup(Application $app)
    {
        $app['config'] = $this->readConfig();

        if (isset($app['config'][self::RABBIT])) {
            $app[self::RABBIT] = $this->connectToRabbitMqServer($app);
        }
    }

    /**
     * Connect the controller classes to the routes
     */
    public function connect(Application $app)
    {
        $this->setup($app);

        $routing = $app['controllers_factory'];

        Controllers\Main::addRoutes($routing);

        return $routing;
    }

    /**
     * Read config file
     *
     * @throws \Exception
     * @return array
     */
    public function readConfig()
    {
        if (!file_exists($configDir = __DIR__ . '/../../config/config.yml')) {
            throw new \Exception("Not found config.yml file", 500);
        }

        $config = new Parser();
        return $config->parse(file_get_contents($configDir));
    }

    public function connectToRabbitMqServer(Application $app)
    {
        try {
            $config = $app['config'][self::RABBIT];
            $connection = new AMQPConnection($config['host'], $config['port'], $config['user'], $config['password']);
            return $connection->channel();
        } catch (AMQPRuntimeException $e) {
            $app['monolog']->addError($e->getMessage());
        }
    }
} 