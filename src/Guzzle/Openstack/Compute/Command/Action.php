<?php

/**
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Guzzle\Openstack\Compute\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Sends a servers API request post
 *
 * @guzzle Data doc="Data" required="true"
 * @guzzle Id doc="Id of the server" required=true
 */
class Action extends AbstractJsonCommand
{

    /**
     * Set the personality - Optional
     *
     * @param array $personality Personality array with keys 'path' and 'contents'.
     *
     * @return Action
     */
    public function setData($data)
    {
        return $this->set('data', $data);
    }

    /**
     * Sets the server id
     * @param type $id
     * @return type Action
     */
    public function setServerId($id)
    {
        return $this->set('serverId', $id);
    }

    protected function build()
    {
        $data = $this->get('data');

        $body = json_encode($data);

        $this->request = $this->client->post('servers/' . $this->get('serverId') . '/action', null, $body);
    }
}