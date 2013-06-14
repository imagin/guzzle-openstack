<?php

namespace Guzzle\Openstack\Openstack;

use Guzzle\Common\Collection;
use Guzzle\Openstack\Identity\IdentityClient;
use Guzzle\Openstack\Compute\ComputeClient;
use Guzzle\Openstack\Common\OpenstackException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Guzzle\Openstack\Common\AuthenticationObserver;

/**
 * @license See the LICENSE file that was distributed with this source code.
 */

/**
 * Openstack Client
 */
class OpenstackClient extends \Guzzle\Service\Client
{
    protected $auth_url, $username, $password, $tenantName, $region, $token;
    protected $tenant;
    protected $user;
    protected $computeClient = array(); 
    protected $identityClient, $serviceCatalog;

    /**
     * Factory method to create a new OpenstackClient
     *
     * @static
     *
     * @param array|Collection $config Configuration data. Array keys:
     *                                 auth_url - Authentication service URL
     *                                 username - API username
     *                                 password - API password
     *                                 tenantName - API tenantName
     *
     * @return \Guzzle\Common\FromConfigInterface|OpenstackClient|\Guzzle\Service\Client
     */
    public static function factory($config = array(), EventDispatcherInterface $eventDispatcher = null)
    {
        $default = array(
            'compute_type' => 'compute',
            'identity_type' => 'identity',
            'storage_type' => 'storage',
            'region' => 'RegionOne'
        );
        
        $required = array('auth_url');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self(
                $config->get('auth_url'), 
                $config->get('username'), 
                $config->get('password'), 
                $config->get('tenantName'),
                $eventDispatcher
        );
        
        $client->setConfig($config);
        $client->region = $config->get('region');
        
        return $client;
    }

    /**
     * OpenstackClient constructor
     *
     * @param string $auth_url URL of the Identity Service
     */
    public function __construct($auth_url, $username, $password, $tenantName = '', EventDispatcherInterface $eventDispatcher = null)
    {
        parent::__construct($auth_url);
        
        $this->auth_url = $auth_url;
        $this->serviceCatalog = array();
        
        $this->identityClient = IdentityClient::factory(array(
            'base_url' => $this->auth_url
        ));
        
        $this->setEventDispatcher($eventDispatcher);
        $this->getEventDispatcher()->addSubscriber(new AuthenticationObserver());
        
        $this->identityClient->setEventDispatcher($eventDispatcher);

        $this->username = $username;
        $this->password = $password;
        $this->tenantName = $tenantName;
    }

    /**
     * Authentication method
     */
    public function authenticate($tenantName = null)
    {
        if (isset($this->token)) {
            return;
        }
        
        $username = $this->username;
        $password = $this->password;
        
        if (!$tenantName) {
            $tenantName = $this->tenantName;
        } else {
            $this->tenantName = $tenantName;
        }
        
        $command = $this->identityClient->getCommand('Authenticate');
        $command->setUsername($username)->setPassword($password)->setTenantname(
                $tenantName
        );
        
        try {
            $authResult = $command->execute();

            //Copy Service Catalog
            $this->serviceCatalog = $authResult['access']['serviceCatalog'];

            //Get token
            $this->token = $authResult['access']['token']['id'];
            $this->identityClient->setToken($this->token);

            $this->tenant = $authResult['access']['token']['tenant'];
            $this->user = $authResult['access']['user'];
        } catch (OpenstackException $e) {
            
        }
    }

    public function getTenantId()
    {
        return $this->tenant['id'];
    }
    
    public function getUserId()
    {
        return $this->user['id'];
    }
    
    public function getServiceCatalog()
    {
        return $this->serviceCatalog;
    }

    /**
     * Get endpoints for the service type for all regions
     *
     * @param string $serviceType
     *
     * @return array
     */
    public function getEndpoints($serviceType)
    {
        if (is_null($this->token)) {
            throw new OpenstackException('Unauthenticated');
        }
        $serviceEndpoints = array();
        foreach ($this->serviceCatalog as $value) {
            if ($value['type'] == $serviceType) {
                $serviceEndpoints = $value['endpoints'];
            }
        }
        return $serviceEndpoints;
    }

    /**
     * Get an endpoint for a specific service and region
     *
     * @param string $serviceType
     * @param string $region
     * @param string $endpointType
     *
     * @return string
     */
    public function getEndpoint($serviceType, $region, $endpointType = 'public')
    {
        $serviceEndpoints = $this->getEndpoints($serviceType);
        foreach ($serviceEndpoints as $endpointsRegion => $endpoints) {
            if ($endpointsRegion == $region) {
                return $endpoints[$endpointType . 'URL'];
            }
        }
    }

    /**
     * @return IdentityClient
     */
    public function getIdentityClient()
    {
        if (!$this->identityClient) {
            $this->identityClient = IdentityClient::factory(
                            array(
                                'username' => $this->username,
                                'password' => $this->password,
                                'tenantName' => $this->tenantName,
                                'base_url' => $this->getEndpoint(
                                        'identity', $this->region, 'admin'
                                )
                            )
            );
        }
        return $this->identityClient;
    }

    /**
     * @return ComputeClient
     */
    public function getComputeClient($tenantId = null)
    {
        if (!isset($this->computeClient[$tenantId])) {
            $computeClient = ComputeClient::factory(
                            array(
                                'token' => $this->token,
                                'base_url' => $this->getEndpoint(
                                    'compute', $this->region, 'admin'
                                ),
                                'tenant_id' => $tenantId, //$this->tenantName,
                            )
            );

            $computeClient->setEventDispatcher($this->getEventDispatcher());

            $this->computeClient[$tenantId] = $computeClient;
        }

        return $this->computeClient[$tenantId];
    }

    /**
     * Returns an authentication token for the specified username / tenant
     *
     * @param string $username
     * @param string $password
     * @param string $tenantid
     * @param string $forceRefresh
     *
     * @return string
     */
    public function getToken($username, $password, $tenantName = '', $forceRefresh = false)
    {
//        $key = $this->createKey($username, $password, $tenantName);
//        if($forceRefresh || !array_key_exists($key, $this->tokenCache)) {
//            $result =  $this->executeAuthCommand($username, $password, $tenantName);
//            $this->tokenCache[$key] = $result['access']['token']['id'];
//        }        
//        return $this->tokenCache[$key];
    }
}

?>
