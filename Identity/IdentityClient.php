<?php

namespace Guzzle\Openstack\Identity;

use Guzzle\Common\Inspector;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Client;
use Guzzle\Service\Description\XmlDescriptionBuilder;

class IdentityClient extends Client
{
   
    /**
     * Factory method to create a new IdentityClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *    username - API username
     *    password - API password
     *    identity - IdentityAuthClient for authentication
     *
     * @return IdentityClient
     */
    public static function factory($config)
    {
        $default = array();
        $required = array('identity', 'username', 'password');
        $config = Inspector::prepareConfig($config, $default, $required);        
        $client = new self($config->get('identity'),$config->get('username'),$config->get('password'));
        $client->setConfig($config);
        $client->getEventManager()->attach(new IdentityAuthObserver(), 0);
        

        $client = new self($config->get('identity')->get('base_url'));
        $client->setConfig($config);

        return $client;
    }

    /**
     * IdentityClient constructor
     *
     * @param IdentityAuthClient $identity IdentityAuthClient for authentication
     * @param string $username Username
     * @param string $password Password
     */
    public function __construct($identity, $username, $password)
    {
        parent::__construct($baseUrl);
        $this->identity = $identity;
        $this->username = $username;
        $this->password = $password;
    }
    
}