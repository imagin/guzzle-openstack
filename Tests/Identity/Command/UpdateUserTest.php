<?php

namespace Guzzle\Openstack\Tests\Identity\Command;

/**
 * Update Users command unit test
 */
class UpdateUsersTest extends \Guzzle\Tests\GuzzleTestCase
{

    public function testUpdateUsers()
    {
        $authclient = \Guzzle\Openstack\IdentityAuth\IdentityAuthClient::factory(array('username' => 'username', 'password' => 'password', 'ip' => '192.168.4.100', 'port'=>'35357'));
        $client = \Guzzle\Openstack\Identity\IdentityClient::factory(array('identity' => $authclient, 'username'=>'username', 'password'=>'password'));
        $this->setMockResponse($client->getIdentity(), 'identity_auth/AuthenticateAuthorized');        
        $this->setMockResponse($client, 'identity/UpdateUser');        
        $command = $client->getCommand('UpdateUser');
        
        $command->setId('2');
        $command->setName('New Name');
        $command->setPassword('New pass');
        $command->setEmail('newemail@email.com');
        $command->setEnabled(false);
        $command->prepare();
        
        //Check method and resource
        $this->assertEquals('http://192.168.4.100:35357/v2.0/users/2', $command->getRequest()->getUrl());
        $this->assertEquals('PUT', $command->getRequest()->getMethod());
                
        //Check for authentication header
        $this->assertTrue($command->getRequest()->hasHeader('X-Auth-Token'));
                        
        $client->execute($command);
      
        $result = $command->getResult();
        $this->assertTrue(is_array($result));
        
        $this->assertTrue(array_key_exists('user', $result));
        
    }
}