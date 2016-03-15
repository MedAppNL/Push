<?php

namespace PharmIT\Push;

use PharmIT\Push\Services\ApplePushService;
use PharmIT\Push\Services\GooglePushService;
use PharmIT\Push\Exceptions\PharmITPushException;

class PushServiceFactory
{
    /**
     * Whether to throw exceptions on errors
     *
     * @var bool Dictates whether exceptions are thrown by default, by default set to true
     */
    private $throwExceptions = true;

    /**
     * Get the push service with the given ID
     *
     * This will also load the configuration for the given service.
     * If this fails, it will not return the service
     *
     * @param string $id The ID of the push service to load. Currently 'apple' and 'google' are supported
     * @return PushService|null The loaded push service or null if the push service could not be loaded
     */
    public function getPushService($id)
    {
        $allowed = ['apple', 'google'];

        $config = config('push');

        if (isset($config['throwExceptionOnError'])) {
            $this->throwExceptions = true && $config['throwExceptionOnError'];
        }

        if (!in_array($id, $allowed)) {
            return $this->handleError();
        }

        $environment = config('app.env');

        $iconfig = [
            "throwExceptionOnError" => $this->throwExceptions
        ];

        if (!isset($config[$id][$environment])) {
            $default = isset($config['defaultEnvironment']) ? $config['defaultEnvironment'] : 'development';

            if (!isset($config[$id][$default])) {
                return $this->handleError();
            } else {
                $iconfig['env'] = $default;
            }

        } else {
            $iconfig['env'] = $environment;
        }

        $service = null;
        switch ($id) {
            case 'apple':
                $service = new ApplePushService();
                break;
            case 'google':
                $service = new GooglePushService();
                break;
            default:
                return null;
        }

        if (!$service->loadConfiguration(array_merge($iconfig, $config[$environment]))) {
            return $this->handleError();
        }

        return $service;
    }

    private function handleError($message = "General push error")
    {
        if ($this->throwExceptions) {
            throw new PharmITPushException($message);
        } else {
            return false;
        }
    }
}
