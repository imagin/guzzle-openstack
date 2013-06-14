<?php

namespace Guzzle\Openstack\Network\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Params:
 * 
 * name: a string specifying a symbolic name for the network, which is not required to be unique;
 * admin_state_up: a bool value specifying the administrative status of the network;
 * shared: a bool value specifying whether this network should be shared across all tenants or not. Note that the default policy setting restrict usage of this attribute to administrative users only;
 * tenant_id
 */
class CreateNetwork extends AbstractJsonCommand
{

    protected function build()
    {
        $body = null;
        $params = $this->getAll(array('tenant_id', 'name', 'admin_state_up', 'shared'));

        if (!empty($params)) {
            $data = array(
                'network' => $params
            );

            $body = json_encode($data);
        }

        $this->request = $this->client->post('networks.json', null, $body);
    }
}