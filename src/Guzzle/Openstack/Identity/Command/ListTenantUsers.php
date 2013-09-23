<?php
/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Identity\Command;

use Guzzle\Openstack\Common\Command\PaginatedCommand;

/**
 * List users for a tenant
 *
 * @guzzle id doc="Id of the tenant to list users for" required="true"
 */
class ListTenantUsers extends PaginatedCommand
{
    
    /**
     * Set the tenant id
     *
     * @param string $id
     *
     * @return ListTenantUsers
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }
    
    protected function build()
    {
        $this->request = $this->client->get('tenants/' . $this->get('id') . '/users');        
        parent::build();
    }
}