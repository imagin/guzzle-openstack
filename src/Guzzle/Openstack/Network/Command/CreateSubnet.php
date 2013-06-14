<?php

namespace Guzzle\Openstack\Network\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Params:
 *  - network_id - required
 *  - ip_version
 *  - cidr - required, network_address/prefix
 *  - allocation_pools
 */
class CreateSubnet extends AbstractJsonCommand
{

    protected function build()
    {
        $body = null;
        $params = $this->getAll(array('name', 'tenant_id', 'network_id', 'ip_version', 'cidr', 'allocation_pools'));

        if (!empty($params)) {
            $data = array(
                'subnet' => $params
            );

            $body = json_encode($data);
        }

        $this->request = $this->client->post('subnets', null, $body);
    }
}