<?php

namespace PharmIT\Push;

class PushServiceFactory
{
    /**
     * Get the push service with the given ID
     *
     * This will also load the configuration for the given service.
     * If this fails, it will not return the service
     *
     * @param string $id
     *   The ID of the push service to load. Currently 'apple' and 'google' are supported
     * @return PushService|null
     *   The loaded push service or null if the push service could not be loaded
     */
    public static function getPushService($id)
    {
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

        if (!$service->loadConfiguration()) {
            return null;
        }

        return $service;
    }
}