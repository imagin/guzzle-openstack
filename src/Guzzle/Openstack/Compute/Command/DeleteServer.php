<?php
/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Compute\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Delete a server
 *
 * @guzzle id doc="Id of the server to delete" required="true"
 */
class DeleteServer extends AbstractJsonCommand
{
    /**
     * Set the server id
     *
     * @param string $id
     *
     * @return DeleteServer
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }
   
    protected function build()
    {       
        $this->request = $this->client->delete('servers/'.$this->get('id'));
    }
}