<?php

/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Compute\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Get image details command
 *
 * @guzzle Id doc="Id of the image" required=true
 */
class ImageDetails extends AbstractJsonCommand
{

    /**
     * Sets the image id
     * @param type $id
     * @return type ImageDetails
     */
    public function setId($id)
    {
        return $this->set('imageId', $id);
    }
    
    protected function build()
    {
        $this->request = $this->client->get('images/'.$this->get('imageId'));        
    }    
    
}

?>
