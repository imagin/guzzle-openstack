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
class AddUserRole extends AbstractJsonCommand {

    
    /**
     * Set the tenant id
     *
     * @param int $tenantId
     *
     * @return AddUserRole
     */
    public function setTenantId($tenantId)
    {
        return $this->set('tenantId', $tenantId);
    }   
    
    /**
     * Set the user id
     *
     * @param int $id
     *
     * @return AddUserRole
     */
    public function setUserId($id)
    {
        return $this->set('userId', $id);
    }   
    
    /**
     * Set the role id
     *
     * @param int $id
     *
     * @return AddUserRole
     */
    public function setRoleId($id)
    {
        return $this->set('roleId', $id);
    }   
    
    protected function build()
    {       
        //tenants/{tenantId}/users/{userId}/roles/OS-KSADM/{roleId}
        $this->request = $this->client->put(sprintf("tenants/%s/users/%s/roles/OS-KSADM/%s", 
            $this->get('tenantId'),
            $this->get('userId'),
            $this->get('roleId')
        ));
    }
}

?>
