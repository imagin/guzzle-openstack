<?php
/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Identity\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Requests a new token for username
 *
 * @guzzle username doc="Username" required="true"
 * @guzzle password doc="Password" required="true"
 * @guzzle tenantId doc="Tenant Id"
 */
class Authenticate extends AbstractJsonCommand
{
    /**
     * Set the username
     *
     * @param string $username
     *
     * @return Authenticate
     */
    public function setUsername($username)
    {
        return $this->set('username', $username);
    }

    /**
     * Set the password
     *
     * @param string $password
     *
     * @return Authenticate
     */
    public function setPassword($password)
    {
        return $this->set('password', $password);
    }

    /**
     * Set the tenant_id
     *
     * @param string $tenantId
     *
     * @return Authenticate
     */
    public function setTenantId($tenantId)
    {
        return $this->set('tenantId', $tenantId);
    }

    /**
     * Set the tenant name
     *
     * @param string $tenantName
     *
     * @return Authenticate
     */
    public function setTenantName($tenantName)
    {
        return $this->set('tenantName', $tenantName);
    }    
    
    protected function build()
    {
        $data = array(
            'auth' => array(
                'passwordCredentials' => array(
                    'username' => $this->get('username'),
                    'password' => $this->get('password')
                ),
            )
        );
        
        if($this->hasKey('tenantName') && !is_null($this->get('tenantName'))){
            $data['auth']['tenantName'] = $this->get('tenantName');
        }
        $body = json_encode($data);
        $this->request = $this->client->post('tokens', null, $body);        
    }
}