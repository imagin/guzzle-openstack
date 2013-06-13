<?php

/**
 * @license See the LICENSE file that was distributed with this source code.
 */
namespace Guzzle\Openstack\Identity\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Command to create a user
 * @guzzle email doc="Email of the new user" required="true"
 * @guzzle name doc="Name of the new user" required="true"
 * @guzzle password doc="Password of the new user" required="true"
 * @guzzle tenantId doc="Tenant id of the new user" required="true"
 * @guzzle enabled doc="Enabled state of new user"
 */
class CreateUser extends AbstractJsonCommand {

    /**
     * Set the username
     *
     * @param string $username
     *
     * @return CreateUser
     */
    public function setUserName($username)
    {
        return $this->set('username', $username);
    }
    
    /**
     * Set the user name
     *
     * @param string $name
     *
     * @return CreateUser
     */
    public function setName($name)
    {
        return $this->set('name', $name);
    }
    
    /**
     * Set the user email
     *
     * @param string $email
     *
     * @return CreateUser
     */
    public function setEmail($email)
    {
        return $this->set('email', $email);
    }
    
    /**
     * Set the user password
     *
     * @param string $password
     *
     * @return CreateUser
     */
    public function setPassword($password)
    {
        return $this->set('password', $password);
    }
    
    /**
     * Set the tenant id
     *
     * @param int $tenantId
     *
     * @return CreateUser
     */
    public function setTenantId($tenantId)
    {
        return $this->set('tenantId', $tenantId);
    }    
    
    /**
     * Set the user status
     *
     * @param boolean $enabled
     *
     * @return CreateTenant
     */
    public function setEnabled($enabled)
    {
        return $this->set('enabled', $enabled);
    }        
    
    protected function build()
    {       
        $data = array(
            "user" => array(
                "username"=> $this->get('username'),
                "name"=> $this->get('name'),
                "email" => $this->get('email'),
                "OS-KSADM:password" => $this->get('password'),
                "tenantId" => $this->get('tenantId')
            )
        );
        
        if($this->hasKey('enabled')){
            $data['user']['enabled'] = $this->get('enabled');
        }

        $body = json_encode($data);        
        $this->request = $this->client->post('users', null, $body);
    }
}

?>
