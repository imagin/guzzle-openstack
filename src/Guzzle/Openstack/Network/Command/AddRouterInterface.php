<?php

namespace Guzzle\Openstack\Network\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Params:
 * 
 * subnet_id
 */
class AddRouterInterface extends AbstractJsonCommand
{

    protected function build()
    {
        $data = array(
            'subnet_id' => $this->get('subnet_id')
        );

        $body = json_encode($data);

        $this->request = $this->client->put('routers/' . $this->get('router_id') .'/add_router_interface.json', null, $body);
    }
}