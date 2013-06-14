<?php

namespace Guzzle\Openstack\Network;

use Guzzle\Common\Collection;
use Guzzle\Openstack\Common\AbstractClient;
use Guzzle\Openstack\Common\AuthenticationObserver;

class NetworkClient extends AbstractClient
{
    protected $baseUrl;

    /**
     * Factory method to create a new NetworkClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *                                 base_url - Base URL of web service
     *                                 token - Authentication token
     *                                 tenant_id Tenant id
     *
     * @return \Guzzle\Common\FromConfigInterface|NetworkClient|\Guzzle\Service\Client
     */
    public static function factory($config = array())
    {
        $default = array();
        $required = array('base_url', 'token');
        $config = Collection::fromConfig($config, $default, $required);
        $client = new self($config->get('base_url'), $config->get('token'));
        $client->setConfig($config);
        $client->getEventDispatcher()->addSubscriber(new AuthenticationObserver());
        return $client;
    }

    /**
     * ComputeClient constructor
     *
     * @param string $baseUrl Base URL for Network
     * @param string $token   Authentication token
     */
    public function __construct($baseUrl, $token)
    {
        parent::__construct($baseUrl);
        $this->setToken($token);
    }
}