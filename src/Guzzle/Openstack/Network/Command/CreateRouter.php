<?php

namespace Guzzle\Openstack\Network\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 * Params:
 *  - name - required
 *  - admin_state_up
 */
class CreateRouter extends AbstractJsonCommand
{

    protected function build()
    {
        $body = null;
        $params = $this->getAll(array('tenant_id', 'name', 'admin_state_up', 'external_gateway_info'));

        if (!empty($params)) {
            $data = array(
                'router' => $params
            );

            $body = json_encode($data);
        }

        $this->request = $this->client->post('routers.json', null, $body);
    }
}