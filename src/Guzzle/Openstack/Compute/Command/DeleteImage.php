<?php
/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Compute\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Delete an image
 *
 * @guzzle id doc="Id of the image to delete" required="true"
 */
class DeleteImage extends AbstractJsonCommand
{
    /**
     * Set the image id
     *
     * @param string $id
     *
     * @return DeleteImage
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }
   
    protected function build()
    {       
        $this->request = $this->client->delete('images/'.$this->get('id'));
    }
}