<?php

namespace Guzzle\Openstack\Network\Command;

use Guzzle\Openstack\Common\Command\AbstractJsonCommand;

/**
 */
class ListNetworks extends AbstractJsonCommand
{

    protected function build()
    {
        $this->request = $this->client->get('networks');
    }
}